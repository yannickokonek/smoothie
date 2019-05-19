<template>
	<b-card>
		<b-row>
			<b-col>
				<h4>Rezept erstellen</h4>
				<label class="sr-only" for="recipeName">Name</label>
				<input class="mb-2" id="recipeName"
					:placeholder="recipeNamePlaceholder"
					v-model="recipeName"
					/>
				<label class="sr-only" for="description">Zutaten
				</label>
				<search
					v-bind:defaultText="enterFruit"
					v-bind:availableItems="existingIngredients"
					v-on:update-list="updateIngredients">
				</search>
				<div>
					<ul>
						<li class="editable-ingredient"
							v-for="item in ingredientList">
							<b-row>
								<b-col cols="5">
									<display-ingredient v-bind:item="item"></display-ingredient>
								</b-col>
								<b-col>
									<edit-ingredient
										v-bind:item="item"
										v-bind:index="index">
									</edit-ingredient>
								</b-col>
								</b-row>
						</li>
					</ul>
				</div>
				<label class="sr-only" for="description">Zubereitung</label>
					<textarea id="description"
						class="mb-2"
						:placeholder="recipeDescription"
						v-model="description"
						v-on:keyup.enter="updateDescription"
						>
					</textarea>
			</b-col>
			<b-col>
				<h4>Vorschau</h4>
				<h4 >{{recipeName}}</h4>

				<h5>Zutaten:</h5>
				<ul>
					<li v-for="item in ingredientList">
						<display-ingredient v-bind:item="item"></display-ingredient>
					</li>
				</ul>
				<h5>Zubereitung:</h5>
				<p>{{description}}</p>
			</b-col>
		</b-row>
					<b-button variant="primary" class="pull-right"
						:prop="recipe"
						v-on:click="save">
					Rezept speichern</b-button>
	</b-card>
</template>
<script type="text/javascript">

	import listCard from './list_card.vue';
	import search from './search.vue';
	import editIngredient from './edit_ingredient.vue';
	import displayIngredient from './display_ingredient.vue';
	export default {
		components: {
			listCard,
			search,
			editIngredient,
			displayIngredient
		},
		data () {
			return {
				recipeNamePlaceholder: 'Rezeptname',
				recipeName: '',
				ingredient: '',
				ingredientList:[],
				recipeDescription:  'Zubereitung:',
				description:'',
				recipe: {},
				enterFruit: "Gib eine Zutat ein",
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
				this.ingredientList = this.$_.filter(this.ingredientList, function(f){return f.name != ingredient.name});
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
	li.editable-ingredient {
		width: 100%;
	}

	#description, input{
		margin-left: 0px;
		margin-top: 0px;
		border-radius: .5em
	}

</style>