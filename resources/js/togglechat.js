export function toggleChat() {
    var chatbotContainer = document.querySelector('#chatbot-container');

    // Si el chatbot no tiene la clase 'show', significa que está oculto y se debe mostrar
    if (!chatbotContainer.classList.contains('show')) {
        chatbotContainer.style.display = 'block'; // Asegura que el contenedor sea visible
        setTimeout(function() { // Añadimos un retraso para que la animación ocurra después de que el contenedor se muestre
            chatbotContainer.classList.add('show'); // Añadimos la clase 'show' para que la animación ocurra
        }, 10);
    } else {
        // Si el chatbot ya está visible, lo ocultamos
        chatbotContainer.classList.remove('show'); // Elimina la clase 'show' para que la animación lo oculte
        setTimeout(function() { // Añadimos un retraso antes de ocultarlo completamente
            chatbotContainer.style.display = 'none'; // Lo ocultamos después de la animación
        }, 300); // El tiempo debe coincidir con el de la transición
    }
}