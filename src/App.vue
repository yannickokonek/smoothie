<template>
	<div id="app">
		<div class="row">
			<div class="col-sm-4 col-xs-12">
				<b-card
					title="Verfügbare Zutaten"
					sub-title="Hier siehst Du die eingegebenen Zutaten"
					>
					<list-card
						v-bind:display="displayIngredients"
						v-bind:defaultText="enterIngredient"
						v-bind:availableItems="ingredients"
						v-on:update-list="findRecipes">
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
				<create-recipe
					v-bind:existingIngredients="ingredientList"
					v-on:save-recipe="addRecipe">
				</create-recipe>
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
	import createRecipe from './create_recipe.vue';
	//Search.vue
	export default {
		name: 'app',
		components: {
			listCard:listCard,
			createRecipe:createRecipe,
		},
		data () {
			return {
				text1:'',
				enterIngredient: 'Gib eine Zutat ein',
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
			updateIngredientList() {

			},
			// addFruite searches the entered ingredient in the total list of ingredients and adds it
			// to the current list, if it existed
			addFruit() {
				var ingredient = this.$_.findWhere(this.ingredients, {name: this.ingredient});				
				this.ingredientList.push(ingredient);
				this.ingredientList = this.$_.unique(this.ingredientList);
				this.ingredient = '';
				this.findRecipes();
			},
			removeFruit(list) {                   
				// this.ingredientList = this.$_.filter(this.ingredientList, function(f){return f.name != ingredient.name});
				debugger;
				this.ingredients = list
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
			findRecipes(list) {
				this.ingredientList = list || this.ingredientList;
				this.matchingAllIngredients = [];
				this.matchingMostIngredients = [];
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
				if (item===undefined) {
					return '';
				}
				return item.name;
			},
			addRecipe(recipe) {
				// todo
				console.log('adding recipe: ', recipe);
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
body {
	background-color:#ddd;
	padding:30px;
}
.card {	
	margin-bottom:30px;
}
</style>
