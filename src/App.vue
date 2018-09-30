<template>
    <div id="app">
        <b-card
            title="Suche"
            sub-title="Finde Dein Smoothie-Rezept, indem Du Zutaten eingibst"
            >
            <label>Zutaten</label>
            <input 
                :placeholder="placeholder"
                v-model="fruit"
                v-on:keyup.enter="addFruit">
            </input>
            <div>
                <v-icon name="sync" scale="2" spin/>
            </div>
        </b-card>
        <div class="row">
            <div class="col-sm-6">
                <b-card
                    title="Verfügbare Früchte"
                    sub-title="Hier siehst Du Deine eingegebenen Früchte">
                    <ul>
                        <li v-for="fruit in fruitList"
                        :key="fruit.name"
                        >{{fruit.name}}
                            <v-icon
                                v-on:click="removeFruit(fruit)"
                            >remove_circle</v-icon></li>
                    </ul>
                </b-card>
            </div>
            <div class="col-sm-6">
                <b-card
                    title="Gefundene Rezepte"
                    sub-title="Hier siehst Du die Ergebnisse">
                    <ul>
                        <li v-for="recipe in matchingRecipes"
                        :key="fruit"
                        >{{recipe.name}}                             
                        </li>
                    </ul>
                </b-card>
            </div>
        </div>
    </div>

</template>

<script>
    import {_} from 'vue-underscore';
    import rezepte from './assets/data/rezepte.json';
    import zutaten from './assets/data/zutaten.json';
    //Search.vue
    export default {
        name: 'app',
        data () {
            return {
                text1:'',
                placeholder: 'Gib eine Frucht ein',
                fruitList: [],
                fruit:'',
                recipes: rezepte,
                ingredients: zutaten,
                matchingRecipes:[],
            }
        },
        methods: {
            addFruit() {
                // todo:
                // find fruit in list and push that to the list
                var fruit = this.$_.findWhere(this.ingredients, {name: this.fruit});
                this.fruitList.push(fruit);
                this.fruitList = this.$_.unique(this.fruitList);
                this.fruit = '';
                this.findRecipes();
            },
            removeFruit(fruit) {                   
                this.fruitList = this.$_.filter(this.fruitList, function(f){return f.name != fruit.name});
                this.findRecipes();
            },
            containsNothingElse(recipe) {
                var self = this;
                var found = true;
                this.$_.each(recipe.ingredients, function(i) {
                    found = found && ( -1 < self.$_.findIndex(self.fruitList, function(fruit) {return fruit.name === i.name;}) );
                });
                return found; 
            },

            // finds all recipes that contains at most the given ingredients
            findRecipes() {
                if (this.fruitList.length === 0){
                    return [];
                }
                var self = this;
                this.matchingRecipes = this.$_.filter(this.recipes, function(recipe) {return self.containsNothingElse(recipe);});
            },
        },
        computed: {
        }
    }
</script>

<style lang="scss">
label {
    padding-top:4px;
    padding-left:5px;
    display:block;
}
input {
    display:block;
    padding:5px;
    padding-left:10px;
    border-radius:5px;
    border-style:solid;
    border-color:#ddd;
    box-shadow: 3px 3px 5px #ddd;
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
