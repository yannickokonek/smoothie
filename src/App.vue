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
                var fruit = _.findWhere(this.ingredients, {name: this.fruit});
                this.fruitList.push(fruit);
                this.fruitList = _.unique(this.fruitList);
                this.fruit = '';
                this.findrecipe();
            },
            removeFruit(fruit) {                   
                this.fruitList = _.filter(this.fruitList, function(f){return f.name != fruit.name});
                this.findrecipe();
            },
            // finds all recipes that contains at most the given ingredients
            findrecipe() {
                var recipes = [];
                var self = this;
                _.each(this.recipes, function(recipe){
                    if (self.containsAll(recipe)){
                        recipes.push(recipe);
                    }
                });
                this.matchingRecipes = recipes;  
            },
            containsAll(recipe) {
                if (this.fruitList.length === 0){
                    return false;
                }
                var found = true;
                var foundCurrent = true;
                _.each(this.fruitList, function(fruit) {
                    foundCurrent = _.find(recipe.ingredients, function(ingd){return ingd.name === fruit.name;});
                    found = found && (foundCurrent != undefined);
                });
                return found;
            }
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
