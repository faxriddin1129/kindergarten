<?php

/* @var $chat_id */

?>
<div id="app" class="row">
    <div class="d-flex justify-content-end">
        <a class="btn btn-danger btn-sm mt-2 me-4" href="/control/logout?chat_id=<?=$chat_id?>">Chiqish =></a>
    </div>
    <div v-if="dataClient" class="col-md-6 p-5">
        <h6>Habarlar</h6>
        <div class="card card-body bg-white border border-secondary mina" ref="messageContainer">
            <div class="d-flex flex-column">
                <div v-for="(msg, index) in messages" :key="index" class="border border-secondary rounded-3 p-2 mb-2"  :class="msg.admin ? 'align-self-end' : 'align-self-start bg-danger text-white'">
                    <div><b>Xabar: </b> {{msg.message}} </div>
                    <div><b>Vaqt: </b> {{ formatDate(msg.created) }}</div>
                    <div><b>Admin: </b> {{ msg.admin ?? msg.user_name }}</div>
                </div>

            </div>
        </div>
    </div>
    <div v-if="dataClient" class="col-md-6 p-5">
        <h6>Habar Yuborish</h6>
        <div class="card card-body bg-white border border-secondary mina">
            <div>
                <div><b>Balance: </b>{{dataClient.balance}}</div>
                <div class="d-flex gap-2">
                   <div> <b>Holati: </b></div>
                    <div v-if="dataClient.status == 1" class="text-warning">
                        Javob kutmoqda
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div v-else>Faol emas</div>
                </div>
            </div>
            <form action="/control/create" method="get">
                <input type="hidden" name="chat_id" value="<?=$chat_id?>">
                <textarea required id="mes" class="form-control form-control-sm" name="message" placeholder="Xabarni yozing..."></textarea>
                <div>
                    <button class="btn btn-sm btn-success mt-1" type="submit">Yuborish</button>
                </div>
            </form>
            <hr>
            <h6 class="mt-3">Tayyor variantlar</h6>


            <div class="d-flex gap-2">

                <div class="border-right border-secondary">
                    <form action="/control/create" method="get">
                        <input type="hidden" name="chat_id" value="<?=$chat_id?>">
                        <input type="hidden" name="message" value="Mofaqqiyatli ovoz berildi va balanisingizga pul tushurildi!">
                        <button class="btn btn-sm btn-success mt-1" type="submit">Mofaqqiyatli ovoz berildi va balanisingizga pul tushurildi!</button>
                    </form>
                </div>

                <div>
                    <form action="/control/create" method="get">
                        <input type="hidden" name="chat_id" value="<?=$chat_id?>">
                        <input type="hidden" name="message" value="Pul kartangizga tushurildi! Hamkorlik qilganizngiz uchun rahmat.">
                        <button class="btn btn-sm btn-danger mt-1" type="submit">Pul kartangizga tushurildi! Hamkorlik qilganizngiz uchun rahmat.</button>
                    </form>
                    <form action="/control/create" method="get">
                        <input type="hidden" name="chat_id" value="<?=$chat_id?>">
                        <input type="hidden" name="message" value="Pul raqamingizga paynet qilindi! Hamkorlik qilganizngiz uchun rahmat.">
                        <button class="btn btn-sm btn-danger mt-1" type="submit">Pul raqamingizga paynet qilindi! Hamkorlik qilganizngiz uchun rahmat.</button>
                    </form>
                </div>

            </div>

            <hr>

        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.0.0/axios.min.js"></script>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
    const { createApp, ref, onMounted, nextTick } = Vue
    createApp({
        setup() {
            const messages = ref(null)
            const dataClient = ref(null)
            const CHAT_ID = "<?=$chat_id?>";
            const messageContainer = ref(null);

            const getData = () => {
                let Url = `https://simply.uz/control/update?chat_id=${CHAT_ID}`
                axios.get(Url)
                    .then(response => {
                        dataClient.value = response.data
                        messages.value = JSON.parse(response.data.messages)
                        nextTick(() => {
                            scrollToBottom();
                        });
                    })
            }
            const formatDate = (timestamp) => {
                let date = new Date(timestamp * 1000);
                return date.toLocaleString();
            };
            const scrollToBottom = () => {
                if (messageContainer.value) {
                    messageContainer.value.scrollTop = messageContainer.value.scrollHeight;
                }
            };

            const refResh = function (){
                setTimeout(() => {
                    getData()
                    refResh()
                },5000)
            }

            onMounted(() => {
                getData()
                refResh()
            })

            return {
                messages,
                getData,
                formatDate,
                messageContainer,
                dataClient
            }
        }
    }).mount('#app')
</script>
<style>
    .mina{
        height: 650px;
        overflow-y: scroll;
    }
    *{
        font-size: 18px!important;
    }
</style>

