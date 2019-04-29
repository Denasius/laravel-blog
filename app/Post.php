<?php

namespace App;

use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use Sluggable;
    protected $fillable = ['title', 'content', 'date', 'description'];

    const IS_DRAFT = 0;
    const IS_PUBLIC = 1;
    const IS_FEATURED = 1;
    const IS_STANDART = 0;
    public function category()
    {
    	return $this->belongsTo(Category::class);
    }

    public function author()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
    	return $this->belongsToMany(
    		Tag::class, 
    		'post_tags',
    		'post_id', 
    		'tag_id'
    	);
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function add($fields)
    {
        $post = new static;
        $post->fill($fields);
        $post->user_id = Auth::user()->id;
        $post->save();

        return $post;
    }

    public function edit($fields)
    {
        $this->fill($fields);
        $this->save();
    }

    public function remove()
    {
        $this->removeImage();
        $this->delete();
    }

    public function uploadImage($image)
    {
        if ($image == null) {return;}

        $this->removeImage();

        $filename = str_random(10) . '.' . $image->extension(); 
        $image->storeAs('uploads', $filename);
        $this->image = $filename;
        $this->save();
    }

    public function removeImage()
    {
        if ( $this->image != null ) {
            Storage::delete('uploads/' . $this->image);
        }
    }

    public function getImage()
    {
        if ( $this->image == null ) {
            return '/img/no-image.png';
        }

        return '/uploads/' . $this->image;
    }

    public function setCategory($id)
    {
        if ($id == null) {return;}

        $this->category_id = $id;
        $this->save();
    }

    public function setTags($ids)
    {
        if ($ids == null) {return;}

        $this->tags()->sync($ids);
    }

    public function setDraft()
    {
        $this->status = Post::IS_DRAFT;
        $this->save();
    }

    public function setPublic()
    {
        $this->status = Post::IS_PUBLIC;
        $this->save();
    }

    public function toggleStatus($value)
    {
        if ($value == null) {
            return $this->setDraft();
        }

        return $this->setPublic();
    }

    public function setFeatured()
    {
        $this->is_featured = Post::IS_FEATURED;
        $this->save();
    }

    public function setStandart()
    {
        $this->is_featured = Post::IS_STANDART;
        $this->save();
    }

    public function toggleFeatured($value)
    {
        if ($value == null) {
            return $this->setStandart();
        }

        return $this->setFeatured();
    }

    // Устанавливаю mutator для изменения формата даты
    public function setDateAttribute($value)
    {
        $date = Carbon::createFromFormat('d/m/y', $value)->format('Y-m-d');
        $this->attributes['date'] = $date;
    }

    // Устанавливаю accessor для вывода даты из БД в нужном мне формате
    public function getDateAttribute($value)
    {
        $date = Carbon::createFromFormat('Y-m-d', $value)->format('d/m/y');
        return $date;
    }

    // Метод для вывода категорий поста
    public function getcategoryTitle()
    {
        return ($this->category != null) 
                ? $this->category->title
                : 'Категория не задана';
    }

    // Метод для вывода тегов поста
    public function getTagsTitles()
    {
        return (!$this->tags->isEmpty())
                ? implode(', ', $this->tags->pluck('title')->all())
                : 'Теги не заданы';
    }

    public function getCategoryID()
    {
        return $this->category != null ? $this->category->id : null;
    }

    public function getDate()
    {
        return Carbon::createFromFormat('d/m/y', $this->date)->format('F d, Y');
    }

    /*
    метод для отображения предыдушего поста
    Запрос в БД, выбираю посты по id, у которых id меньше, чем у текущего
    Из выдернутых из БД id выбираю максимальный, он и будет предыдущим постом
    */
    public function hasPrevious()
    {
        
        return self::where('id', '<', $this->id)->max('id');
    }

    public function getPrevious()
    {
        $postID = $this->hasPrevious(); // возвратит ID
        return self::find($postID);
    }

    // то же самое, что и с hasPrevious, только выбираю больше текущего и с минимальным значением
    public function hasNext()
    {
        return self::where('id', '>', $this->id)->min('id');
    }

    public function getNext()
    {
        $postID = $this->hasNext(); 
        return self::find($postID);
    }

    public function related()
    {
        return self::all()->except($this->id);
    }

    // Проверка есть у поста категории или нет
    public function hasCategory()
    {
        return $this->category != null ? true : false;
    }

    // Вывод коментарикв
    public function getComments()
    {
        return $this->comments()->where('status', 1)->get();
    }
}
