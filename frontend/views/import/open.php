<?php
// Yii2 view fayli (masalan, index.php yoki boshqa view)
?>

<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script type="module">
    import 'https://unpkg.com/mathlive?module';
</script>

<div id="app">
    <h3>Formulalarni kiriting:</h3>
    <form method="POST" action="/index.php?r=site/formula" @submit.prevent="onSubmit">

        <label>Formula 1:</label>
        <math-field v-on:input="updateField('latex1', $event)" style="width: 100%; font-size: 1.2em;"></math-field>
        <input type="hidden" name="latex1" :value="latex1">

        <label>Formula 2:</label>
        <math-field v-on:input="updateField('latex2', $event)" style="width: 100%; font-size: 1.2em;"></math-field>
        <input type="hidden" name="latex2" :value="latex2">

        <label>Formula 3:</label>
        <math-field v-on:input="updateField('latex3', $event)" style="width: 100%; font-size: 1.2em;"></math-field>
        <input type="hidden" name="latex3" :value="latex3">

        <br><br>
        <button type="submit">Yuborish</button>
    </form>

    <div style="margin-top:20px">
        <p><strong>Formula 1:</strong> {{ latex1 }}</p>
        <p><strong>Formula 2:</strong> {{ latex2 }}</p>
        <p><strong>Formula 3:</strong> {{ latex3 }}</p>
    </div>
</div>

<script>
    new Vue({
        el: '#app',
        data: {
            latex1: '',
            latex2: '',
            latex3: ''
        },
        methods: {
            updateField(field, event) {
                this[field] = event.target.value;
            },
        }
    });
</script>
