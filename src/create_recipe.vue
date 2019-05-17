<template>
	<b-card
		title="Rezept hinzufügen">
		<p>Wenn Du ein neues Rezept hinzufügst, werden die verwendeten Zutaten automatisch zu den verfügbaren Zutaten hinzugefügt</p>
		<b-row>
			<b-col>
				<label for="recipeName">Name</label>
				<input class="mb-2 mr-sm-2 mb-sm-0" id="recipeName"
					:placeholder="recipeNamePlaceholder"
					v-model="recipeName"
					/>
				<label for="description">Zutaten
				</label>
				<search
					v-bind:defaultText="enterFruit"
					v-bind:availableItems="existingIngredients"
					v-on:update-list="updateIngredients">
				</search>
				<div>
					<ul>
						<li class="editable-ingredient"
							v-for="(item, index) in ingredientList">
							<b-row>
								<b-col cols="5">
									<span class="text-large">{{item.name}}</span>
									<span class="text-medium">{{item.quantity}} {{item.unit}}</span>
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
			</b-col>
			<b-col>
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
			</b-col>
		</b-row>
	</b-card>
</template>
<script type="text/javascript">

	import listCard from './list_card.vue';
	import search from './search.vue';
	import editIngredient from './edit_ingredient.vue';
	export default {
		components: {
			listCard,
			search,
			editIngredient
		},
		data () {
			return {
				recipeNamePlaceholder: 'Rezeptname',
				recipeName: '',
				ingredient: '',
				ingredientList:[],
				recipeDescription:  'Anleitung',
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
	.text-large {
		font-size: 1.2em;
		font-weight: 511;
		color: #666;
	}
	.text-medium {
		font-size: 1.1em;
		font-weight: 511;
		color: #666;
	}
</style>