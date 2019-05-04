<template>
	<b-card
		title="Rezept hinzufügen">
		<p>Wenn Du ein neues Rezept hinzufügst, werden die verwendeten Zutaten automatisch zu den verfügbaren Zutaten hinzugefügt</p>
		<div class="row">
			<div class="col-lg-6">
				<label for="recipeName">Name</label>
				<input class="mb-2 mr-sm-2 mb-sm-0" id="recipeName"
					:placeholder="recipeNamePlaceholder"
					v-model="recipeName"
					/>
				<label for="description">Zutaten
				</label>
				<list-card v-bind:list="ingredientList"
					v-bind:defaultText="enterFruit"
					v-bind:display="function(item) {return item.name}"
					v-bind:availableItems="existingIngredients"
					v-on:update-list="updateIngredients"
					>
				</list-card>
			</div>
			<div class="col-lg-6">
			<label>Zubereitung</label>
				<textarea id="description"
					:placeholder="recipeDescription"
					v-model="description"
					v-on:keyup.enter="updateDescription"
					>
				</textarea>
				<b-button variant="primary" class="pull-right"
					:prop="recipe"
					v-on:click="save">
				Rezept speichern</b-button>
			</div>
		</div>
	</b-card>
</template>
<script type="text/javascript">

	import listCard from './list_card.vue';
	export default {
		components: {
			listCard
		},
		data () {
			return {
				recipeNamePlaceholder: 'Rezeptname',
				recipeName: '',
				ingredientPlaceholder: 'gib eine Zutat ein',
				ingredient: '',
				ingredientList:[],
				recipeDescription:  'Anleitung',
				description:'',
				recipe: {},
				enterFruit: "Gib eine Zutat ein",
				// the data to use in this template (aside from properties)
			}
		},
		props: {
			existingIngredients: {
				type: Array,
				required: true,
			},
		},
		methods: {
			updateDescription: function() {
				this.recipe.description = this.description;
				console.log(this.recipe);
			},
			updateIngredients: function(list) {
				this.ingredientList = list;
			},
			addItem: function() {
				this.ingredientList.push(this.ingredient);
				this.ingredient = ''
			},
			removeIngredient: function(ingredient) {
				this.ingredientList = this.$_.filter(this.ingredientList, function(f){return f.name != ingredient});
			},
			save: function() {
				this.recipe.ingredients = this.ingredientList;
				this.recipe.name = this.recipeName;
				this.recipe.instructions = this.description;
				this.$emit('save-recipe', this.recipe);
			}
		}
	}
</script>
<style type="text/css">
	textarea {
		width:100%;
		padding: 10px;
		margin: 10px;
		border-style:solid;

	}
	.pull-right {
		float: right;
	}
</style>