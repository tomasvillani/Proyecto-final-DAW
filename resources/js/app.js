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

import {toggleChat} from './togglechat.js';

if(document.querySelector('#chatbot-icon')){
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
    
        chatbotIcon.addEventListener("click", () => {
            chatbotContainer.classList.toggle("active");
        });
    
        closeButton.addEventListener("click", () => {
            chatbotContainer.classList.remove("active");
        });
    
        const api_key = "O4MWBzixAdoAz8R3mhAEGAgpAXjmqj5sJXb1OHOD"; // API Key Cohere
        let chatHistory = []; // Guarda el historial de mensajes
    
        function sendMessage() {
            let userMessage = chatbotInput.value.trim();
            if (userMessage === "") return;
    
            appendMessage("Tú", userMessage, "user-message");
    
            chatbotInput.value = "";
            chatbotInput.disabled = true;
            sendButton.disabled = true;
    
            let loadingMessage = appendMessage("GymBot", "<span class='typing'>Escribiendo<span>.</span><span>.</span><span>.</span></span>", "bot-message-loading");
    
            // Guardar la conversación en el historial
            chatHistory.push({ role: "USER", message: userMessage });
    
            const requestData = {
                model: "command-r-plus",
                message: userMessage,
                temperature: 0.7,
                chat_history: chatHistory,
                preamble: `Eres GymBot, un asistente virtual especializado en gimnasio, fitness y nutrición. 
                Tu objetivo es ayudar a los usuarios con rutinas de ejercicio, dietas y consejos saludables. 
                Responde siempre de manera corta, amigable y útil.
                
                Cuando alguien te pida una rutina de entrenamiento, responde con el siguiente formato:
                
                Ejercicio : series x repeticiones  
                Por ejemplo:  
                - Sentadillas : 4x12  
                - Flexiones : 3x15  
                - Plancha : 3x30s  
    
                Si te preguntan sobre dietas, ofrece opciones de comidas saludables para ganar masa muscular o perder grasa.`
            };
    
            fetch("https://api.cohere.ai/v1/chat", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": `Bearer ${api_key}`
                },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.text) {
                    const botMessage = data.text.trim();
    
                    loadingMessage.innerHTML = `<strong>GymBot:</strong> ${botMessage}`;
                    loadingMessage.classList.remove("bot-message-loading");
    
                    chatHistory.push({ role: "CHATBOT", message: botMessage });
                } else {
                    loadingMessage.innerHTML = `<strong>GymBot:</strong> Error al procesar tu mensaje.`;
                }
            })
            .catch(error => {
                console.error("Error:", error);
                loadingMessage.innerHTML = `<strong>GymBot:</strong> Error de conexión.`;
            })
            .finally(() => {
                chatbotInput.disabled = false;
                sendButton.disabled = false;
            });
        }
    
        function appendMessage(sender, message, className) {
            let messageElement = document.createElement("div");
            messageElement.classList.add("message", className);
            messageElement.innerHTML = `<strong>${sender}:</strong> ${message}`;
            chatbotMessages.appendChild(messageElement);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
            return messageElement;
        }
    
        chatbotInput.addEventListener("keypress", function (event) {
            if (event.key === "Enter") sendMessage();
        });
    
        sendButton.addEventListener("click", sendMessage);
    });
}

window.Alpine = Alpine;

Alpine.start();
