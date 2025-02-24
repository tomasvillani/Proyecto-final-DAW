document.addEventListener("DOMContentLoaded", function () {
    const chatbotBtn = document.getElementById("chatbot-btn");
    const chatbotContainer = document.getElementById("chatbot-container");
    const closeChatbot = document.getElementById("close-chatbot");

    chatbotBtn.addEventListener("click", function () {
        chatbotContainer.classList.toggle("hidden");
    });

    closeChatbot.addEventListener("click", function () {
        chatbotContainer.classList.add("hidden");
    });
});
