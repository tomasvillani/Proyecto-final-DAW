import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'bootstrap';

import '../css/flaticon/flaticon.css';
import '../css/bootstrap.min.css';
import '../css/style.css';
import '../css/owlcarousel/owlcarousel.min.css';
import '../css/estilos.css';

import './easing.min.js';
import './waypoints.min.js';
import './counterup.min.js';
import './owl.carousel.min.js';

import './main.js';

import Alpine from 'alpinejs';

function toggleChat() {
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

// Event listener para el ícono de chatbot
document.querySelector('#chatbot-icon').addEventListener('click', toggleChat);

// Event listener para el botón de cerrar del chatbot
document.querySelector('#chatbot-header i').addEventListener('click', toggleChat);

document.addEventListener("DOMContentLoaded", function () {
    const chatbotIcon = document.querySelector("#chatbot-icon");
    const chatbotContainer = document.querySelector("#chatbot-container");
    const chatbotMessages = document.querySelector("#chatbot-messages");
    const chatbotInput = document.querySelector("#chatbot-input");
    const sendButton = document.querySelector("#send-button");
    const closeButton = document.querySelector("#chatbot-header i");

    // Mostrar u ocultar el chatbot
    chatbotIcon.addEventListener("click", () => {
        chatbotContainer.classList.toggle("active");
    });

    closeButton.addEventListener("click", () => {
        chatbotContainer.classList.remove("active");
    });

    const api_key="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoiNzA1NTMyNTUtOTA3YS00NTg2LTg2YWItYjI1NThlZmVhMGY3IiwidHlwZSI6ImFwaV90b2tlbiJ9.Baa8LJXbzwrUIWMvqGzWltgEUToMMmiIEQ7I_VNtTfg";

    function sendMessage() {
        let userMessage = chatbotInput.value.trim();
        if (userMessage === "") return;
    
        // Mostrar el mensaje del usuario en el chat
        appendMessage("Tú", userMessage, "user-message");
    
        chatbotInput.value = "";
        chatbotInput.disabled = true;
        sendButton.disabled = true;
    
        // Crear la solicitud AJAX para Eden AI
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "https://api.edenai.run/v2/llm/chat", true); // Endpoint correcto
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader("Authorization", `Bearer ${api_key}`); // Reemplázalo con tu API Key real
    
        // Manejar la respuesta
        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    const data = JSON.parse(xhr.responseText);
                    
                    if (data && data.openai && data.openai.generated_text) {
                        let botMessage = data.openai.generated_text; // Verifica la estructura correcta
                        appendMessage("GymBot", botMessage, "bot-message");
                    } else {
                        console.error("Error: Respuesta inesperada de la API", data);
                        appendMessage("GymBot", "Hubo un error al procesar tu mensaje. Intenta de nuevo.", "bot-message");
                    }
                } catch (error) {
                    console.error("Error al parsear la respuesta:", error);
                    appendMessage("GymBot", "Hubo un error al procesar tu mensaje. Intenta de nuevo.", "bot-message");
                }
            } else {
                console.error("Error en la solicitud:", xhr.status, xhr.statusText);
                appendMessage("GymBot", "Hubo un error al procesar tu mensaje. Intenta de nuevo.", "bot-message");
            }
    
            chatbotInput.disabled = false;
            sendButton.disabled = false;
        };
    
        xhr.onerror = function () {
            console.error("Request failed");
            appendMessage("GymBot", "Hubo un error al procesar tu mensaje. Intenta de nuevo.", "bot-message");
            chatbotInput.disabled = false;
            sendButton.disabled = false;
        };
    
        // Enviar la solicitud con el formato correcto
        var requestData = JSON.stringify({
            providers: ["openai"], // Cambia a "deepseek" si es el que usas
            model: "gpt-3.5-turbo",
            messages: [
                { role: "system", content: "Eres un chatbot de gimnasio que ayuda con entrenamientos, dietas y rutinas." },
                { role: "user", content: userMessage }
            ],
            max_tokens: 150,
            temperature: 0.7
        });
    
        xhr.send(requestData);
    }
        

    // Función para agregar mensajes al chat
    function appendMessage(sender, message, className) {
        let messageElement = document.createElement("div");
        messageElement.classList.add("message", className);
        messageElement.innerHTML = `<strong>${sender}:</strong> ${message}`;
        chatbotMessages.appendChild(messageElement);
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    // Evento para enviar mensaje con "Enter"
    chatbotInput.addEventListener("keypress", function (event) {
        if (event.key === "Enter") sendMessage();
    });

    // Evento para enviar mensaje con el botón
    sendButton.addEventListener("click", sendMessage);
});


window.Alpine = Alpine;

Alpine.start();
