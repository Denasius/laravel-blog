<?php

namespace App\Http\Controllers\Admin;

use App\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    public function index()
    {
    	$categories = Category::all();

    	return view('admin.categories.index', ['categories'=>$categories]);
    }

    public function create()
    {
    	return view('admin.categories.create');
    }

    public function store(Request $request)
    {
    	// Валидация формы
    	$this->validate($request, [
    		'title' => 'required' // обязательное поле
    	]);
    	// Запрос в БД и ее заполнение 
    	Category::create($request->all());
    	return redirect()->route('categories.index');
    }

    public function edit($id)
    {
    	// Сделать запрос в БД и вывести категорию
    	$category = Category::find($id);
    	return view('admin.categories.edit', ['category' => $category]);
    }

    public function update(Request $request, $id)
    {
    	$this->validate($request, [
    		'title' => 'required'

    	]);
    	// Найти в БД таблицу по id
    	$category = Category::find($id);

    	// Обновляю таблицу по всем полям
    	$category->update($request->all());
    	return redirect()->route('categories.index');
    }

    public function destroy($id)
    {
    	// Нахожу в БД таблицу с нужным id и удаляю ее
    	Category::find($id)->delete();
    	return redirect()->route('categories.index');
    }
}
