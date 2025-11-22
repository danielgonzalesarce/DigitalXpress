<?php

/**
 * PageController
 * 
 * Controlador para páginas estáticas/informativas de DigitalXpress.
 * Maneja la visualización de páginas de contenido estático:
 * - Centro de ayuda
 * - Garantías
 * - Devoluciones
 * - Contacto
 * - Sobre nosotros
 * - Términos y condiciones
 * - Política de privacidad
 * - Blog
 * - Página de desarrollo (en construcción)
 * 
 * Todas estas páginas son públicas y no requieren autenticación.
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Mostrar página del Centro de Ayuda
     * 
     * @return \Illuminate\View\View Vista del centro de ayuda
     */
    public function helpCenter()
    {
        return view('pages.help-center');
    }

    /**
     * Mostrar página de Garantías
     * 
     * @return \Illuminate\View\View Vista de garantías
     */
    public function warranties()
    {
        return view('pages.warranties');
    }

    /**
     * Mostrar página de Devoluciones
     * 
     * @return \Illuminate\View\View Vista de devoluciones
     */
    public function returns()
    {
        return view('pages.returns');
    }

    /**
     * Mostrar página de Contacto
     * 
     * @return \Illuminate\View\View Vista de contacto
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * Mostrar página Sobre Nosotros
     * 
     * @return \Illuminate\View\View Vista sobre nosotros
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * Mostrar página de Términos y Condiciones
     * 
     * @return \Illuminate\View\View Vista de términos
     */
    public function terms()
    {
        return view('pages.terms');
    }

    /**
     * Mostrar página de Política de Privacidad
     * 
     * @return \Illuminate\View\View Vista de privacidad
     */
    public function privacy()
    {
        return view('pages.privacy');
    }

    /**
     * Mostrar página del Blog
     * 
     * @return \Illuminate\View\View Vista del blog
     */
    public function blog()
    {
        return view('pages.blog');
    }

    /**
     * Mostrar página de Desarrollo (En Construcción)
     * 
     * Página temporal para funcionalidades en desarrollo.
     * 
     * @return \Illuminate\View\View Vista de desarrollo
     */
    public function development()
    {
        return view('pages.development');
    }
}

