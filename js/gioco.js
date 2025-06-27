document.addEventListener("DOMContentLoaded", () => {
    const symbols = ['🍕', '🎈', '🐱', '🚀', '🌈', '🍩', '🎮', '🐸'];
    let cards = [...symbols, ...symbols];
    let firstCard = null;
    let lockBoard = false;
    let matches = 0;

    const gameBoard = document.getElementById("gameBoard");
    const status = document.getElementById("gameStatus");

    shuffle(cards);

    cards.forEach(symbol => {
        const card = document.createElement("div");
        card.classList.add("card");
        card.dataset.symbol = symbol;
        card.innerHTML = "❓";
        card.addEventListener("click", flipCard);
        gameBoard.appendChild(card);
    });

    function flipCard() {
        if (lockBoard || this.classList.contains("flipped")) return;

        this.classList.add("flipped");
        this.innerHTML = this.dataset.symbol;

        if (!firstCard) {
            firstCard = this;
            return;
        }

        if (firstCard.dataset.symbol === this.dataset.symbol) {
            matches++;
            firstCard = null;
            if (matches === symbols.length) {
                status.textContent = "🎉 Hai vinto! Complimenti!";
            }
        } else {
            lockBoard = true;
            setTimeout(() => {
                firstCard.classList.remove("flipped");
                this.classList.remove("flipped");
                firstCard.innerHTML = "❓";
                this.innerHTML = "❓";
                firstCard = null;
                lockBoard = false;
            }, 1000);
        }
    }

    function shuffle(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
    }
});