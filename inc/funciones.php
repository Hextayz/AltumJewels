<?php


function iniciarSesion() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function formatearPrecio($precio) {
    return number_format($precio, 2, '.', ',') . ' €';
}

function agregarAlCarrito($producto) {
    iniciarSesion();
    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = [];
    }
    $_SESSION['carrito'][] = $producto;
}

function eliminarDelCarrito($indice) {
    iniciarSesion();
    if (isset($_SESSION['carrito'][$indice])) {
        unset($_SESSION['carrito'][$indice]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }
}
