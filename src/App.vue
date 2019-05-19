<template>
	<div id="app">
		<b-row>
			<b-col cols="12" md="4">
				<b-card
					title="Verfügbare Zutaten"
					sub-title="Hier siehst Du die eingegebenen Zutaten"
					>
					<search
						v-bind:defaultText="enterIngredient"
						v-bind:availableItems="ingredients"
						v-on:update-list="findRecipes">
					</search>

					<list-card
						v-bind:display="displayIngredients"
						v-bind:list="ingredientList"
						v-on:remove-item="removeIngredient"item
					</list-card>
				</b-card>
			</b-col>
			<b-col cols="12" sm="6" md="4">
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
			</b-col>
			<b-col cols="12" sm="6" md="4">
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
			</b-col>
		</b-row>
		<b-row>
			<b-col>
				<create-recipe
					v-bind:existingIngredients="ingredients"
					v-on:save-recipe="addRecipe">
				</create-recipe>
			</b-col>
		</b-row>
	</div>

</template>

<script type="text/javascript">
	import {_} from 'vue-underscore';
	import rezepte from './assets/data/rezepte.json';
	import zutaten from './assets/data/zutaten.json';
	import listCard from './list_card.vue';
	import createRecipe from './create_recipe.vue';
	import search from './search.vue';
	export default {
		name: 'app',
		components: {
			listCard:listCard,
			createRecipe:createRecipe,
			search,
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
			removeIngredient(item) {
				this.ingredients = this.$_.filter(this.ingredients, function(f){return f.name != item.name});
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
				this.updateIngredientList(recipe.ingredients);
				this.recipes.push(recipe)
			},
			updateIngredientList(list) {
				var self = this;
				for (var i=0; i<list.length; i++) {
					var x =this.$_.findWhere(this.ingredients, {"name":list[i].name});
					if (!x) {
						this.ingredients.push(list[i]);
					}
				}
			},
		},
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
	background: url(assets/images/fruite_background.jpg);
	background-repeat: no-repeat;
	background-attachment: fixed;
	background-size:cover;
	padding:30px;
}
.card {
	margin-bottom:30px;
	opacity:0.95;
}
div.card {
	border-radius: .5em;
}
</style>
