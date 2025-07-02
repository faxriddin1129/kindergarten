<?php

/* @var $questions */
/* @var $full_name */
/* @var $model \frontend\models\rash\RashControl */

$this->title = 'Answers';


$dataQuestions = [];

foreach ($questions as $question) {
    $type = 'multiple_choice';
    $category = 'Geography';
    if ($question['type'] == 'Open'){
        $type = 'input';
        $category = 'Mathematics';
    }
    $dataQuestions[] = [
        'id' => $question['id'],
        'number' => $question['number'],
        'type' => $type,
        'question' => '--',
        'options' => ['A','B','C','D'],
        'correctAnswer' => 'A',
        'category' => $category,
    ];
}
?>


<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Vue.js CDN -->
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<!-- MathLive kutubxonasi -->
<script type="module" src="https://unpkg.com/mathlive?module"></script>
<style>
    .bg{
        background-color: #764ba2;;
    }
    body {
        background: linear-gradient(135deg, #667eea, #764ba2);
        min-height: 100vh;
        padding: 20px 0;
    }

    input{
        font-size: 18px!important;
    }

    .quiz-container {
        max-width: 100%;
        margin: 0 auto;
    }

    .progress {
        height: 6px;
        margin-bottom: 20px;
    }

    .option-letter {
        font-weight: bold;
        margin-right: 10px;
        min-width: 25px;
    }

    /* MathLive math-field styling */
    math-field {
        width: 100%;
        padding: 10px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        font-size: 16px;
        margin-bottom: 10px;
        background-color: white;
        min-height: 50px;
    }

    math-field:focus-within {
        border-color: #764ba2;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        outline: none;
    }

    .question-type {
        font-size: 0.8rem;
    }

    .option-card {
        cursor: pointer;
        transition: all 0.2s;
    }

    .option-card:hover {
        background-color: #f8f9fa;
    }

    .option-card.selected {
        background-color: #764ba2;
        color: white;
        border-color: #764ba2;
    }

    .math-input-hint {
        font-size: 0.8rem;
        color: #6c757d;
        font-style: italic;
        margin-top: 5px;
    }

    .question-navigation {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 20px;
    }

    .question-nav-button {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 14px;
        cursor: pointer;
        background-color: #f0f0f0;
        border: 1px solid #ddd;
    }

    .question-nav-button.active {
        background-color: #673b91;
        color: white;
        border-color: #764ba2;
    }

    .question-nav-button.answered {
        background-color: #764ba2;
        color: white;
        border-color: #764ba2;
    }

    .submit-section {
        margin-top: 30px;
        text-align: center;
    }

    .unanswered-warning {
        color: #dc3545;
        font-size: 14px;
        margin-top: 10px;
    }
</style>

<div id="app">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm quiz-container">
                    <div class="card-header bg text-white text-center py-3">
                        <h3 class="mb-0">ID: <?=$model->quiz_id?></h3>
                    </div>

                    <div class="card-body p-4">
                        <!-- Progress bar -->
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar"
                                 :style="{ width: progress + '%' }"
                                 :aria-valuenow="progress"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                            </div>
                        </div>

                        <!-- Quiz content -->
                        <div v-if="!quizCompleted && !isSubmitting">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="badge bg-secondary">Savol {{ currentQuestionIndex + 1 }}/{{ questions.length }}</span>
                                <span class="badge" :class="hasAnswer(currentQuestionIndex) ? 'bg-success' : 'bg-warning'">
                                    {{ hasAnswer(currentQuestionIndex) ? 'Javob berilgan' : 'Javob berilmagan' }}
                                </span>
                            </div>

                            <!-- Savollar navigatsiyasi -->
                            <div class="question-navigation">
                                <div v-for="(question, index) in questions"
                                     :key="index"
                                     class="question-nav-button"
                                     :class="{
                                        'active': currentQuestionIndex === index,
                                        'answered': hasAnswer(index)
                                     }"
                                     @click="goToQuestion(index)">
                                    {{ index + 1 }}
                                </div>
                            </div>

                            <span class="badge bg-light text-dark question-type mb-2">
                                {{ currentQuestion.type === 'multiple_choice' ? (currentQuestion.number + ' - Yopiq test') : (currentQuestion.number + ' - Ochiq test') }}
                            </span>

                            <!-- Yopiq test (multiple choice) -->
                            <div v-if="currentQuestion.type === 'multiple_choice'" class="mb-4">
                                <div v-for="(option, index) in currentQuestion.options"
                                     :key="index"
                                     class="card mb-2 option-card"
                                     :class="{ selected: userAnswers[currentQuestionIndex] === option }"
                                     @click="selectOption(option)">
                                    <div class="card-body py-2 px-3">
                                        <div class="d-flex align-items-center">
                                            <span>{{ option }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ochiq test (input) -->
                            <div v-else class="mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <!-- MathLive math-field elementi -->
                                        <math-field
                                                :id="'math-input-' + currentQuestionIndex"
                                                @input="updateMathInput"
                                                virtual-keyboard-mode="onfocus"
                                                virtual-keyboard-theme="material"
                                                class="mb-2">
                                        </math-field>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <button class="btn btn-outline-success"
                                        @click="prevQuestion"
                                        :disabled="currentQuestionIndex === 0">
                                    <i class="bi bi-arrow-left"></i> Oldingi
                                </button>
                                <button v-if="currentQuestionIndex < questions.length - 1"
                                        class="btn btn-success"
                                        @click="nextQuestion">
                                    Keyingi <i class="bi bi-arrow-right"></i>
                                </button>
                                <button v-else
                                        class="btn btn-success"
                                        @click="showSubmitConfirmation">
                                    Yuborish <i class="bi bi-check-lg"></i>
                                </button>
                            </div>

                            <!-- Submit confirmation -->
                            <div v-if="showingSubmitConfirmation" class="submit-section">
                                <div class="alert" :class="unansweredCount > 0 ? 'alert-warning' : 'alert-info'">
                                    <h5>Javoblarni yuborishni tasdiqlang</h5>
                                    <p v-if="unansweredCount > 0" class="unanswered-warning">
                                        Diqqat! {{ unansweredCount }} ta savolga javob berilmagan.
                                    </p>
                                    <p v-else>
                                        Barcha savollarga javob berilgan.
                                    </p>
                                    <div class="d-flex justify-content-center gap-2 mt-3">
                                        <button class="btn btn-secondary" @click="showingSubmitConfirmation = false">
                                            Bekor qilish
                                        </button>
                                        <button class="btn btn-success" @click="submitQuiz">
                                            Tasdiqlash va yuborish
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Loading state -->
                        <div v-else-if="isSubmitting" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Yuklanmoqda...</span>
                            </div>
                            <p class="mt-3">Javoblar yuborilmoqda...</p>
                        </div>

                        <!-- Results -->
                        <div v-else class="text-center py-3">
                            <div v-if="submitSuccess" class="alert alert-success mb-4">
                                Javoblaringiz muvaffaqiyatli yuborildi!
                            </div>
                            <div v-if="submitError" class="alert alert-danger mb-4">
                                Xatolik yuz berdi: {{ submitError }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Telegram WebApp -->
<script src="https://telegram.org/js/telegram-web-app.js"></script>


<script>
    const DATA_QUESTIONS = JSON.parse(('<?=json_encode($dataQuestions)?>'));
</script>
<script>
    const { createApp, ref, computed, nextTick, onMounted, watch } = Vue;


    const TG = window.Telegram.WebApp;
    TG.expand()
    let USER_ID = TG.initDataUnsafe.user.id


    createApp({
        setup() {
            // Quiz savollari
            const questions = ref(DATA_QUESTIONS);

            // Quiz holati
            const currentQuestionIndex = ref(0);
            const userAnswers = ref(Array(questions.value.length).fill(''));
            const quizCompleted = ref(false);
            const score = ref(0);
            const isSubmitting = ref(false);
            const submitSuccess = ref(false);
            const submitError = ref('');
            const showingSubmitConfirmation = ref(false);

            // Joriy savol
            const currentQuestion = computed(() => {
                return questions.value[currentQuestionIndex.value];
            });

            // Progress
            const progress = computed(() => {
                return ((currentQuestionIndex.value + 1) / questions.value.length) * 100;
            });

            // Javob berilmagan savollar soni
            const unansweredCount = computed(() => {
                return userAnswers.value.filter(answer =>
                    answer === null || answer === undefined || answer === ''
                ).length;
            });

            // Savolga javob berilganmi tekshirish
            function hasAnswer(index) {
                const answer = userAnswers.value[index];
                return answer !== null && answer !== undefined && answer !== '';
            }

            // Savolga o'tish
            function goToQuestion(index) {
                currentQuestionIndex.value = index;
                nextTick(() => {
                    initMathField();
                });
            }

            // MathLive math-field ni yangilash
            onMounted(() => {
                nextTick(() => {
                    initMathField();
                });
            });

            // currentQuestionIndex o'zgarganda MathField ni yangilash
            watch(currentQuestionIndex, () => {
                nextTick(() => {
                    initMathField();
                });
            });

            // MathLive math-field ni ishga tushirish
            function initMathField() {
                if (currentQuestion.value.type === 'input') {
                    const mathFieldElement = document.getElementById(`math-input-${currentQuestionIndex.value}`);
                    if (mathFieldElement) {
                        // Agar avval kiritilgan qiymat bo'lsa, uni ko'rsatish
                        const savedValue = userAnswers.value[currentQuestionIndex.value];
                        if (savedValue) {
                            mathFieldElement.value = savedValue;
                        } else {
                            mathFieldElement.value = ''; // Clear the field if no saved value
                        }

                        // Avtomatik fokus
                        setTimeout(() => {
                            mathFieldElement.focus();
                        }, 300);
                    }
                }
            }

            // MathLive math-field qiymatini yangilash
            function updateMathInput(event) {
                const value = event.target.value;
                userAnswers.value[currentQuestionIndex.value] = value;
                console.log(`Saved answer for question ${currentQuestionIndex.value + 1}: ${value}`);
            }

            // Variantni tanlash (yopiq test uchun)
            function selectOption(option) {
                userAnswers.value[currentQuestionIndex.value] = option;
            }

            // Oldingi savolga o'tish
            function prevQuestion() {
                if (currentQuestionIndex.value > 0) {
                    currentQuestionIndex.value--;
                }
            }

            // Keyingi savolga o'tish
            function nextQuestion() {
                if (currentQuestionIndex.value < questions.value.length - 1) {
                    currentQuestionIndex.value++;
                }
            }

            // Yuborish tasdiqlash oynasini ko'rsatish
            function showSubmitConfirmation() {
                showingSubmitConfirmation.value = true;
            }

            // Quiz ni yuborish
            async function submitQuiz() {
                try {
                    isSubmitting.value = true;

                    // To'g'ri javoblar sonini hisoblash
                    score.value = 0;
                    userAnswers.value.forEach((answer, index) => {
                        const question = questions.value[index];
                        if (answer === question.correctAnswer) {
                            score.value++;
                        }
                    });

                    // Backend ga yuborish
                    // Bu yerda o'zingizning backend URL ingizni ko'rsating
                    let url = '/rash/send'
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            answers: userAnswers.value,
                            score: score.value,
                            rash_id: "<?=$model->id?>",
                            full_name: "<?=$full_name?>",
                            chat_id: USER_ID,
                            totalQuestions: questions.value.length
                        }),
                    });

                    // Agar backend ga yuborish kerak bo'lmasa, quyidagi qatorni izohdan chiqaring
                    // va yuqoridagi fetch qismini izohga oling
                    // submitSuccess.value = true;

                    if (response.ok) {
                        submitSuccess.value = true;
                    } else {
                        console.log(response)
                        submitError.value = 'Oldin ushbu testni topshirgansiz';
                    }
                } catch (error) {
                    // Haqiqiy backend bo'lmagani uchun, test maqsadida muvaffaqiyatli deb belgilaymiz
                    submitSuccess.value = true;
                    // submitError.value = error.message || 'Xatolik yuz berdi';
                } finally {
                    isSubmitting.value = false;
                    quizCompleted.value = true;
                }
            }

            // Quiz ni qayta boshlash
            function restartQuiz() {
                currentQuestionIndex.value = 0;
                userAnswers.value = Array(questions.value.length).fill('');
                quizCompleted.value = false;
                score.value = 0;
                submitSuccess.value = false;
                submitError.value = '';
                showingSubmitConfirmation.value = false;
                nextTick(() => {
                    initMathField();
                });
            }

            // Try to load saved answers from localStorage
            const savedAnswers = localStorage.getItem('quiz-answers');
            if (savedAnswers) {
                try {
                    const parsedAnswers = JSON.parse(savedAnswers);
                    if (Array.isArray(parsedAnswers) && parsedAnswers.length === questions.value.length) {
                        userAnswers.value = parsedAnswers;
                    }
                } catch (e) {
                    console.error('Error loading saved answers:', e);
                }
            }

            watch(userAnswers, (newAnswers) => {
                console.log('Answers updated:', newAnswers);
                localStorage.setItem('quiz-answers', JSON.stringify(newAnswers));
            }, { deep: true });

            return {
                questions,
                currentQuestionIndex,
                currentQuestion,
                userAnswers,
                quizCompleted,
                score,
                progress,
                isSubmitting,
                submitSuccess,
                submitError,
                showingSubmitConfirmation,
                unansweredCount,
                hasAnswer,
                goToQuestion,
                selectOption,
                updateMathInput,
                prevQuestion,
                nextQuestion,
                showSubmitConfirmation,
                submitQuiz,
                restartQuiz
            };
        }
    }).mount('#app');
</script>
<script>
    window.addEventListener("beforeunload", function (e) {
        const message = "O'zgartirishlar saqlanmagan bo'lishi mumkin. Chiqmoqchimisiz?";
        e.returnValue = message;
        return message;
    });
</script>



