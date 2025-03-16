

function obtenerCarritoLocal() {
    const data = localStorage.getItem('carrito_altum');
    return data ? JSON.parse(data) : [];
}

function guardarCarritoLocal(carrito) {
    localStorage.setItem('carrito_altum', JSON.stringify(carrito));
}

function agregarProductoLocal(product) {
    let carrito = obtenerCarritoLocal();
    carrito.push(product);
    guardarCarritoLocal(carrito);
    alert("Producto agregado al carrito (JS localStorage).");
}

function eliminarProductoLocal(indice) {
    let carrito = obtenerCarritoLocal();
    if (indice >= 0 && indice < carrito.length) {
        carrito.splice(indice, 1);
        guardarCarritoLocal(carrito);
        alert("Producto eliminado del carrito local.");
    }
}
