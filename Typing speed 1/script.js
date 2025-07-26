const textDisplay = document.getElementById('text-display');
const textInput = document.getElementById('text-input');
const startBtn = document.getElementById('start-btn');
const timerDisplay = document.getElementById('timer');
const wpmDisplay = document.getElementById('wpm');
const accuracyDisplay = document.getElementById('accuracy');
const historyList = document.getElementById('history-list');
const difficultyBtns = document.querySelectorAll('.difficulty-btn');

let isTestActive = false;
let startTime;
let totalTyped = 0;
let correctTyped = 0;
let currentDifficulty = 'easy';
let timerInterval;

const sampleTexts = {
    easy: [
        "The quick brown fox jumps over the lazy dog.",
        "Hello world! Welcome to the typing test.",
        "Practice makes perfect. Keep typing!",
        "The sun is bright and the sky is blue.",
        "Learning to type faster is fun and useful.",
        "The cat sat on the mat and purred softly.",
        "I love to read books and write stories.",
        "The weather is nice today for a walk.",
        "My favorite color is blue and green.",
        "The birds are singing in the morning."
    ],
    medium: [
        "The quick brown fox jumps over the lazy dog. This pangram contains every letter of the English alphabet at least once.",
        "Programming is the process of creating a set of instructions that tell a computer how to perform a task.",
        "The Internet is a global network of billions of computers and other electronic devices.",
        "Artificial intelligence is the simulation of human intelligence processes by machines.",
        "Cloud computing is the delivery of computing services over the Internet.",
        "The World Wide Web has revolutionized how we access and share information globally.",
        "Mobile applications have become an essential part of our daily lives.",
        "Data science combines statistics, programming, and domain expertise to extract insights.",
        "Cybersecurity is crucial for protecting digital information and systems.",
        "Virtual reality technology creates immersive, computer-generated environments."
    ],
    hard: [
        "The quick brown fox jumps over the lazy dog. This pangram contains every letter of the English alphabet at least once. Programming is the process of creating a set of instructions that tell a computer how to perform a task. The Internet is a global network of billions of computers and other electronic devices.",
        "Artificial intelligence is the simulation of human intelligence processes by machines. Cloud computing is the delivery of computing services over the Internet. Machine learning is a subset of artificial intelligence that focuses on developing systems that can learn from and make decisions based on data.",
        "The World Wide Web, commonly known as the Web, is an information system where documents and other web resources are identified by Uniform Resource Locators. The Web was invented by Tim Berners-Lee in 1989 while working at CERN.",
        "Quantum computing is a type of computation that harnesses the collective properties of quantum states to perform calculations. Quantum computers use quantum bits, or qubits, which can exist in multiple states simultaneously.",
        "Blockchain is a system of recording information in a way that makes it difficult or impossible to change, hack, or cheat the system. A blockchain is essentially a digital ledger of transactions that is duplicated and distributed across the entire network of computer systems on the blockchain.",
        "Neural networks are computing systems inspired by the biological neural networks that constitute animal brains. They are used to recognize patterns and solve complex problems in artificial intelligence.",
        "The Internet of Things (IoT) refers to the network of physical objects embedded with sensors, software, and other technologies for the purpose of connecting and exchanging data with other devices and systems over the Internet.",
        "Augmented reality (AR) is an interactive experience of a real-world environment where the objects that reside in the real world are enhanced by computer-generated perceptual information.",
        "Big data refers to data sets that are too large or complex to be dealt with by traditional data-processing application software. It involves analyzing and extracting information from these large data sets.",
        "Cybersecurity is the practice of protecting systems, networks, and programs from digital attacks. These cyberattacks are usually aimed at accessing, changing, or destroying sensitive information."
    ]
};

function getRandomText() {
    const texts = sampleTexts[currentDifficulty];
    return texts[Math.floor(Math.random() * texts.length)];
}

function startTest() {
    if (isTestActive) return;
    
    isTestActive = true;
    totalTyped = 0;
    correctTyped = 0;
    
    textDisplay.textContent = getRandomText();
    textInput.value = '';
    textInput.disabled = false;
    textInput.focus();
    startBtn.textContent = 'End Test';
    startBtn.classList.add('end-test');
    
    startTime = new Date();
    
    // Reset displays
    wpmDisplay.textContent = '0';
    accuracyDisplay.textContent = '0';
    timerDisplay.textContent = '0';

    // Start the timer interval
    timerInterval = setInterval(updateTimer, 1000);
}

function endTest() {
    if (!isTestActive) return;
    
    isTestActive = false;
    textInput.disabled = true;
    startBtn.textContent = 'Start Test';
    startBtn.classList.remove('end-test');
    
    // Clear the timer interval
    clearInterval(timerInterval);
    
    const timeTaken = (new Date() - startTime) / 1000 / 60; // in minutes
    const wpm = Math.round((correctTyped / 5) / timeTaken);
    const accuracy = Math.round((correctTyped / totalTyped) * 100) || 0;
    
    wpmDisplay.textContent = wpm;
    accuracyDisplay.textContent = accuracy;
    
    // Save result to history
    saveResult(wpm, accuracy, currentDifficulty);
}

function updateTimer() {
    if (!isTestActive) return;
    const elapsedSeconds = Math.floor((new Date() - startTime) / 1000);
    timerDisplay.textContent = elapsedSeconds;
}

function saveResult(wpm, accuracy, difficulty) {
    // Save to database
    fetch('save_result.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            wpm: wpm,
            accuracy: accuracy,
            difficulty: difficulty
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add to history display
            const result = document.createElement('div');
            result.className = 'result';
            result.innerHTML = `
                <p>WPM: ${wpm} | Accuracy: ${accuracy}% | Difficulty: ${difficulty} | Date: ${new Date().toLocaleString()}</p>
            `;
            historyList.insertBefore(result, historyList.firstChild);
        } else {
            console.error('Failed to save result:', data.error);
        }
    })
    .catch(error => {
        console.error('Error saving result:', error);
    });
}

textInput.addEventListener('input', () => {
    if (!isTestActive) return;
    
    const text = textDisplay.textContent;
    const input = textInput.value;
    totalTyped = input.length;
    
    let correct = 0;
    for (let i = 0; i < input.length; i++) {
        if (input[i] === text[i]) {
            correct++;
        }
    }
    correctTyped = correct;
    
    const accuracy = Math.round((correct / totalTyped) * 100) || 0;
    accuracyDisplay.textContent = accuracy;
    
    const timeTaken = (new Date() - startTime) / 1000 / 60; // in minutes
    const wpm = Math.round((correct / 5) / timeTaken);
    wpmDisplay.textContent = wpm;
});

startBtn.addEventListener('click', () => {
    if (isTestActive) {
        endTest();
    } else {
        startTest();
    }
});

// Get difficulty from URL parameter
const urlParams = new URLSearchParams(window.location.search);
currentDifficulty = urlParams.get('difficulty') || 'easy';
if (!['easy', 'medium', 'hard'].includes(currentDifficulty)) {
    currentDifficulty = 'easy';
}

// Update the text display with a new text for the current difficulty
if (textDisplay) {
    textDisplay.textContent = getRandomText();
}

// Difficulty selection
difficultyBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        // Remove active class from all buttons
        difficultyBtns.forEach(b => b.classList.remove('active'));
        // Add active class to clicked button
        btn.classList.add('active');
        // Update current difficulty
        currentDifficulty = btn.dataset.difficulty;
    });
});

// Set initial difficulty
difficultyBtns[0].classList.add('active');

// Difficulty selector functionality
document.addEventListener('DOMContentLoaded', function() {
    const difficultyToggle = document.getElementById('difficulty-toggle');
    const difficultyOptions = document.getElementById('difficulty-options');
    const difficultyButtons = document.querySelectorAll('.difficulty-btn');

    if (difficultyToggle && difficultyOptions) {
        // Toggle dropdown
        difficultyToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            difficultyOptions.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!difficultyOptions.contains(e.target) && !difficultyToggle.contains(e.target)) {
                difficultyOptions.classList.remove('show');
            }
        });

        // Handle difficulty selection
        difficultyButtons.forEach(button => {
            button.addEventListener('click', function() {
                const difficulty = this.dataset.difficulty;
                // Remove active class from all buttons
                difficultyButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to selected button
                this.classList.add('active');
                // Update toggle button text
                difficultyToggle.innerHTML = `Difficulty: ${difficulty.charAt(0).toUpperCase() + difficulty.slice(1)} <i class="fas fa-chevron-down"></i>`;
                // Hide options
                difficultyOptions.classList.remove('show');
                // Update current difficulty
                currentDifficulty = difficulty;
                // Load new text for selected difficulty
                if (textDisplay) {
                    textDisplay.textContent = getRandomText();
                }
            });
        });
    }
}); 