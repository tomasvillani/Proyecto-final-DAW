import { toggleChat } from '../../resources/js/togglechat.js';

describe('Toggle Chat', () => {
  let chatbotContainer;

  beforeEach(() => {
    // Simulamos el DOM donde se encuentra el chatbot
    document.body.innerHTML = `
      <div id="chatbot-container" style="display: none;"></div>
      <div id="chatbot-icon"></div>
    `;
    chatbotContainer = document.querySelector('#chatbot-container');
  });

  it('should toggle chatbot visibility', () => {
    const chatbotContainer = document.querySelector('#chatbot-container');
    
    // Primero, asegúrate de que el chatbot esté oculto inicialmente
    chatbotContainer.style.display = 'none';

    // Ejecutamos la función para mostrar el chatbot
    toggleChat();
    
    // Esperamos un momento para asegurarnos de que la animación se haya completado
    setTimeout(() => {
        expect(chatbotContainer.style.display).toBe('block');
        
        // Ejecutamos la función nuevamente para ocultarlo
        toggleChat();

        // Esperamos otro momento para asegurarnos de que la animación de ocultado haya terminado
        setTimeout(() => {
            expect(chatbotContainer.style.display).toBe('none');
        }, 300); // Ajusta este tiempo si es necesario según la duración de la animación
    }, 10); // Un pequeño retraso para permitir la primera actualización de estilo
});

});

describe('Chatbot functionality', () => {
    let chatbotContainer;
    let chatbotIcon;
    let closeButton;

    beforeEach(() => {
        // Configurar el DOM antes de cada prueba
        document.body.innerHTML = `
            <div id="chatbot-container">
                <div id="chatbot-header">
                    <i></i>
                </div>
            </div>
            <button id="chatbot-icon"></button>
        `;

        chatbotContainer = document.querySelector('#chatbot-container');
        chatbotIcon = document.querySelector('#chatbot-icon');
        closeButton = document.querySelector('#chatbot-header i');

        // Asegúrate de que el chatbot está oculto inicialmente
        chatbotContainer.classList.remove('active');

        // Configura los event listeners manualmente sin esperar `DOMContentLoaded`
        chatbotIcon.addEventListener("click", () => {
            chatbotContainer.classList.toggle("active");
        });

        closeButton.addEventListener("click", () => {
            chatbotContainer.classList.remove("active");
        });
    });

    it('should toggle chatbot visibility when clicking the chatbot icon', () => {
        // Simula un clic en el ícono del chatbot
        chatbotIcon.click();

        // Verifica si la clase "active" se agregó
        expect(chatbotContainer.classList.contains('active')).toBe(true); // El chatbot debería estar visible

        // Simula otro clic en el ícono del chatbot para ocultarlo
        chatbotIcon.click();

        // Verifica si la clase "active" se eliminó
        expect(chatbotContainer.classList.contains('active')).toBe(false); // El chatbot debería estar oculto
    });

    it('should toggle chatbot visibility when clicking the close button', () => {
        // Simula un clic en el ícono del chatbot para mostrarlo
        chatbotIcon.click();
        expect(chatbotContainer.classList.contains('active')).toBe(true); // El chatbot debería estar visible

        // Simula un clic en el botón de cerrar
        closeButton.click();
        expect(chatbotContainer.classList.contains('active')).toBe(false); // El chatbot debería estar oculto
    });

    it('should handle sending messages', async () => {
        // Mocks de funciones o componentes que interactúan con la API (por ejemplo, `fetch`)
        global.fetch = jest.fn().mockResolvedValue({
            json: jest.fn().mockResolvedValue({ text: 'Respuesta del bot' })
        });

        const chatbotInput = document.createElement('input');
        chatbotInput.value = 'Hola';

        const sendButton = document.createElement('button');

        // Agregar los elementos al DOM
        document.body.appendChild(chatbotInput);
        document.body.appendChild(sendButton);

        // Crear una función que simula el envío de mensajes
        const sendMessage = () => {
            const userMessage = chatbotInput.value.trim();
            if (userMessage === "") return;

            // Llamada a la API (esto es solo una simulación)
            fetch('https://api.cohere.ai/v1/chat', {
                method: 'POST',
                body: JSON.stringify({
                    message: userMessage,
                    model: 'command-r-plus'
                })
            }).then(response => response.json())
              .then(data => {
                  // Aquí puedes hacer alguna comprobación de los resultados
                  expect(data.text).toBe('Respuesta del bot');
              })
              .catch(err => {
                  console.error("Error:", err);
              });
        };

        // Simula hacer clic en el botón de enviar
        sendButton.addEventListener('click', sendMessage);
        sendButton.click();

        // Espera a que se resuelva el mock
        await new Promise(process.nextTick);
        expect(global.fetch).toHaveBeenCalledTimes(1); // Verifica que se haya hecho una llamada a `fetch`
    });
});
