<?php

namespace App\Http\Controllers;

use App\Repositories\GeneralRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Обработчик домашней (index) страницы
     *
     * @param Request $request
     * @return View
     */
    public function __invoke(Request $request): View
    {
        $articles = GeneralRepository::getArticlesList();

        return view('home.index', compact('articles'));
    }
}
