<template>
    <div id="app">
        <b-card
            title="Suche"
            sub-title="Finde Dein Smoothie-Rezept, indem Du Zutaten eingibst"
            >
            <label>Zutaten</label>
            <input 
                :placeholder="placeholder"
                v-model="ingredient"
                v-on:keyup.enter="addFruit">
            </input>
            <div>
                <v-icon name="sync" scale="2" spin/>
            </div>
        </b-card>
        <div class="row">
            <div class="col-sm-4 col-xs-12">
                <b-card
                    title="Verfügbare Zutaten"
                    sub-title="Hier siehst Du die eingegebenen Zutaten"
                    >
                    <list-card v-bind:list="ingredientList"
                        v-bind:display="displayIngredients"
                        v-on:item-event="removeFruit">
                    </list-card>
                </b-card>
            </div>
            <div class="col-sm-4 col-xs-12">
                <b-card
                    title="Gefundene Rezepte"
                    sub-title="Hier siehst Du die Ergebnisse">
                    <ul>
                        <li v-for="recipe in matchingAllIngredients"
                        :key="ingredient"
                        >{{recipe.name}}                             
                        </li>
                    </ul>
                </b-card>
            </div>
            <div class="col-sm-4 col-xs-12">
                <b-card
                    title="Rezepte mit fehlenden Zutaten"
                    sub-title="Hier siehst Du die Ergebnisse">
                    <ul>
                        <li v-for="recipe in matchingMostIngredients"
                        :key="ingredient"
                        >{{recipe.name}}                             
                        </li>
                    </ul>
                </b-card>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <b-card
                    title="Rezept hinzufügen"
                    sub-title="Wenn Du ein neues Rezept hinzufügst, werden die verwendeten Zutaten automatisch zu den verfügbaren Zutaten hinzugefügt">
                    <b-form>
                        <b-form-group>
                            <label class="sr-only" for="inlineFormInputName2">Name</label>
                            <b-input class="mb-2 mr-sm-2 mb-sm-0" id="inlineFormInputName2" placeholder="Rezeptname" />
                        </b-form-group>
                        <b-form-group>
                            <label class="sr-only" for="inlineFormInputGroupUsername2">Zutaten</label>
                            <b-input class="mb-2 mr-sm-2 mb-sm-0" id="inlineFormInputName2" placeholder="Rezeptname" />
                        </b-form-group>
                        <b-form-group>
                            <label class="sr-only" for="inlineFormInputGroupUsername2">Zutaten
                            </label>
                            <b-input-group left="@" class="mb-2 mr-sm-2 mb-sm-0">
                            <b-input id="inlineFormInputGroupUsername2" placeholder="Username" />
                            </b-input-group>
                            <b-button variant="primary">Save</b-button>
                        </b-form-group>
                    </b-form>
                </b-card>
            </div>
            <div class="col-sm-6">
                <b-card
                    title="Zutat hinzufügen"
                    >

                </b-card>
            </div>
        </div>
    </div>

</template>

<script>
    import {_} from 'vue-underscore';
    import rezepte from './assets/data/rezepte.json';
    import zutaten from './assets/data/zutaten.json';
    import listCard from './list_card.vue';
    //Search.vue
    export default {
        name: 'app',
        components: {
            listCard:listCard,
        },
        data () {
            return {
                text1:'',
                placeholder: 'Gib eine Frucht ein',
                ingredientList: [],
                ingredient:'',
                recipes: rezepte,
                ingredients: zutaten,
                matchingAllIngredients:[],
                matchingMostIngredients:[],
                fields: [
                    {
                        name: "Zutat",
                        placeholder: "placeholder",
                    }
                ]
            };
        },
        methods: {
            addFruit() {
                var ingredient = this.$_.findWhere(this.ingredients, {name: this.ingredient});
                this.ingredientList.push(ingredient);
                this.ingredientList = this.$_.unique(this.ingredientList);
                this.ingredient = '';
                this.findRecipes();
            },
            removeFruit(ingredient) {                   
                this.ingredientList = this.$_.filter(this.ingredientList, function(f){return f.name != ingredient.name});
                this.findRecipes();
            },
            containsNothingElse(recipe) {
                var self = this;
                var found = true;
                this.$_.each(recipe.ingredients, function(i) {
                    found = found && ( -1 < self.$_.findIndex(self.ingredientList, function(ingredient) {return ingredient.name === i.name;}) );
                });
                return found; 
            },
            containsMoreThanHalf(recipe) {
                var self = this;
                var found = 0;
                var neededMatches = Math.floor(recipe.ingredients.length/2);
                this.$_.each(recipe.ingredients, function(i) {
                    if ( -1 < self.$_.findIndex(self.ingredientList, 
                        function(ingredient) {return ingredient.name === i.name;})) {
                        found +=1;
                    }
                });
                return (found >= neededMatches);   
            },

            // finds all recipes that contains at most the given ingredients
            findRecipes() {
                this.matchingAllIngredients = [];
                this.matchingMostIngredients = [];
                if (this.ingredientList.length === 0){
                    return [];
                }
                var self = this;
                for (var i=0; i<this.recipes.length; i++) {
                    if (this.containsNothingElse(this.recipes[i])) {
                        this.matchingAllIngredients.push(this.recipes[i]);
                        continue;
                    } 
                    if (this.containsMoreThanHalf(this.recipes[i])) {
                        this.matchingMostIngredients.push(this.recipes[i]);                      
                    }                     
                }
            },
            displayIngredients(item) {
                return item.name;
            },
            addRecipe() {
                // todo
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
