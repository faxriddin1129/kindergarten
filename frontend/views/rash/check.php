<?php

/** @var $model */
/** @var $full_name */
/** @var $questions */

$dataQuestions = [];

foreach ($questions as $question) {
    $type = 'choice';
    $options = explode(",", $question['format']);
    if ($question['type'] == 'Open'){
        $type = 'input';
        $options = [];
    }
    $dataQuestions[] = [
        'id' => $question['id'],
        'number' => $question['number'],
        'type' => $type,
        'options' => $options,
    ];
}

?>

<!-- Telegram WebApp -->
<script src="https://telegram.org/js/telegram-web-app.js"></script>

<!-- Vue.js CDN -->
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<!-- MathLive CDN -->
<script type="module">
    import 'https://unpkg.com/mathlive?module';
</script>

<style>
    body {
        font-family: sans-serif;
        background-color: white;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
    }

    #app {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        max-width: 800px;
        width: 100%;
    }

    h1 {
        text-align: center;
        color: #333;
        margin-bottom: 30px;
    }

    .question-card {
        padding: 10px;
    }

    .question-card h2 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: #1a202c;
    }

    .options-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .option-button {
        padding: 13px 25px;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.2s, color 0.2s, border-color 0.2s;
        font-weight: 500;
        border: 1px solid #cbd5e0;
        background-color: white;
        color: #4a5568;
    }

    .option-button:hover {
        background-color: #edf2f7;
    }

    .option-button.selected {
        background-color: #764ba2; /* Button rangi */
        color: white;
        border-color: #764ba2;
    }

    .option-button.selected:hover {
        background-color: #6a4291;
    }

    .math-input {
        width: 100%;
        border: 1px solid #cbd5e0;
        border-radius: 6px;
        padding: 10px;
        font-size: 1rem;
        color: #4a5568;
        box-sizing: border-box; /* Ensure padding doesn't increase width */
    }

    .math-input:focus {
        outline: none;
        border-color: #764ba2;
        box-shadow: 0 0 0 2px rgba(118, 75, 162, 0.2);
    }

    .loading-message {
        color: #718096;
        font-size: 0.875rem;
        margin-top: 8px;
    }

    .submit-button-container {
        text-align: center;
        margin-top: 40px;
    }

    .submit-button {
        background-color: #764ba2; /* Button rangi */
        color: white;
        padding: 12px 32px;
        border-radius: 6px;
        font-size: 1.125rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: background-color 0.2s;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .submit-button:hover {
        background-color: #6a4291;
    }
</style>


<div id="app">
    <h6>Test Javoblari Paneli</h6>

    <div v-for="question in questions" :key="question.id" class="question-card">
        <h6>Savol {{ question.number }}</h6>
        <div v-if="question.type === 'choice'" class="options-container">
            <button
                    v-for="option in question.options"
                    :key="option"
                    @click="handleChoiceSelect(question.id, option, 'choice')"
                    :class="['option-button', { 'selected': answers[question.id] && answers[question.id].answer === option }]"
            >
                {{ option }}
            </button>
        </div>
        <div v-else-if="question.type === 'input'">
            <div v-if="mathLiveLoaded">
                <math-field
                        :id="'math-input-' + question.id"
                        virtual-keyboard-mode="onfocus"
                        virtual-keyboard-theme="material"
                        class="math-input"
                        @input="event => handleMathInputChange(question.id, event)"
                        :value="answers[question.id] ? answers[question.id].answer : ''"
                ></math-field>
            </div>
            <div v-else class="loading-message">Matematik klaviatura yuklanmoqda...</div>
        </div>
    </div>

    <div class="submit-button-container">
        <button @click="handleSubmit" class="submit-button" id="sendButton">YUBORISH</button>
    </div>
</div>

<script>
    const {createApp, ref, onMounted} = Vue

    const TG = window.Telegram.WebApp;
    let USER_ID = TG.initDataUnsafe.user.id
    // let USER_ID = 7579528513
    TG.expand()

    createApp({
        setup() {
            const questions = ref(<?=json_encode($dataQuestions)?>)

            const full_name = '<?=$full_name?>'
            const chat_id = USER_ID
            const quiz_id = '<?=$model["id"]?>'

            const answers = ref({})
            questions.value.forEach(q => {
                if (q.type === 'input') {
                    answers.value[q.id] = {answer: '', type: 'input'}
                }
            })

            const mathLiveLoaded = ref(false)

            onMounted(() => {
                // MathLive script is already loaded via CDN in the head,
                // so we just need to confirm it's available.
                // A small delay might be needed to ensure the custom element is registered.
                setTimeout(() => {
                    if (window.MathfieldElement) { // Check if MathfieldElement is globally available
                        mathLiveLoaded.value = true
                        console.log("MathLive is ready.")
                    } else {
                        console.error("MathLive not found after timeout.")
                    }
                }, 1000); // Give it a moment to load
            })

            const handleChoiceSelect = (questionId, selectedOption, type) => {
                answers.value = {
                    ...answers.value,
                    [questionId]: {answer: selectedOption, type},
                }
            }

            const handleMathInputChange = (questionId, event) => {
                // MathLive's custom element has a 'value' property
                const target = event.target
                answers.value = {
                    ...answers.value,
                    [questionId]: {answer: target.value, type: 'input'},
                }
            }

            const handleSubmit = async () => {

                document.getElementById('sendButton').display = true
                document.getElementById('sendButton').innerHTML = 'Loading...'

                const formattedAnswers = Object.entries(answers.value).map(([id, data]) => ({
                    id: parseInt(id),
                    answer: data.answer,
                    type: data.type,
                }))

                const payload = {
                    full_name,
                    quiz_id,
                    chat_id,
                    answers: formattedAnswers,
                }

                console.log("Submitting payload:", payload)

                try {
                    const response = await fetch("https://simply.uz/rash/send", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify(payload),
                    })

                    if (response.ok) {
                        console.log("Answers submitted successfully!")
                        window.location.replace("/rash/success")
                    } else {
                        const errorData = await response.json()
                        console.error("Failed to submit answers:", response.status, response.statusText, errorData)
                        alert('Oldin ushbu testni topshirgansiz')
                    }
                } catch (error) {
                    console.error("Error submitting answers:", error)
                    alert("Tarmoq xatosi: Javoblarni yuborib bo‘lmadi. Iltimos, internet aloqangizni tekshiring.")
                }
            }

            return {
                questions,
                answers,
                mathLiveLoaded,
                handleChoiceSelect,
                handleMathInputChange,
                handleSubmit,
            }
        }
    }).mount("#app")
</script>
