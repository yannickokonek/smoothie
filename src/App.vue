<template>
    <div id="app">
        <b-card
            title="Suche"
            sub-title="Finde Dein Smoothie-Rezept, indem Du Zutaten eingibst"
            >
            <p class="card-text">
                <label>Zutaten</label>
                <input 
                    :placeholder="ph"
                    v-model="fruit"
                    v-on:keyup.enter="addFruit">
                </input>
            </p>
            <div>
                <v-icon name="sync" scale="2" spin/>
            </div>
        </b-card>
        <b-card
            title="Ergebnisse"
            sub-title="Hier siehst Du Deine eingegebenen Früchte">
            <div>home<v-icon>home</v-icon>Icon</div>
            <ul>
                <li v-for="fruit in fruitList">{{fruit}} 
                    <v-icon>remove</v-icon></li>
            </ul>
        </b-card>
    </div>

</template>

<script>
    import {_} from 'vue-underscore';
    //Search.vue
    export default {
        name: 'app',
        data () {
            return {
                text1:'',
                ph: 'Gib eine Frucht ein',
                fruitList: [],
                fruit:'',
            }
        },
        methods: {
            addFruit() {
                this.fruitList.push(this.fruit);
                this.fruitList = _.unique(this.fruitList);
                this.fruit = '';
            },
        },
        computed: {
        }
    }
</script>

<style lang="scss">
label {
    padding-top:4px;
    padding-left:10px;
    float:left;
    display:block;
    margin-left:5px;
}
input {
    display:block;
    padding-left:5px;
    padding-right:5px;
    border-radius:5px;
}
li {
    background-color: coral;
    margin: 5px;
    width: fit-content;
    border-radius: 5px;
}
ul {
    list-style-type:none;
}
</style>
