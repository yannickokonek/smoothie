<template>
	<div class="item-wrapper">
		<div v-if="edit">
		  <b-form inline>
			<label class="sr-only" for="inline-form-input-quantity">Menge</label>
			<b-form-input
				id="inline-form-input-quantity"
				class="mr-sm-2 w-33"
				placeholder="100"
				required
				type="number"
				min="0"
				v-model="item.quantity">
			</b-form-input>

			<label class="sr-only" for="inline-form-input-unit">Unit</label>
			<b-form-select id="inline-form-input-unit" v-model="item.unit"
				:options="options"
				class="mr-sm-2 w-33"
				></b-form-select>
			<b-button variant="primary" class="w-33"
				v-on:click="save">Ok</b-button>

		  </b-form>
		</div>
		<div v-else>
			<b-button variant="primary"
				v-on:click="edit=true">Bearbeiten</b-button>
		</div>
	</div>
</template>
<script type="text/javascript">

	export default {
		data() { return {
				unit:"",
				quantity:100,
				edit:true,
				options: [
				  { value: null, text: 'Einheit wählen' },
				  { value: 'g', text: 'g' },
				  { value: 'ml', text: 'ml' },
				  { value: 'Stück', text: 'Stück' },
				]
			}
		},
		props: {
			item: {
				type: Object,
				required: true
			},
			unitList: {
				type: Array,
				required: false,
			},
		},
		methods: {
			save: function() {
				this.edit = false;
			}
		}
	}
</script>
<style type="text/css">
	.w-33,#inline-form-input-unit.w-33,#inline-form-input-quantity.w-33 {
		width: 30%;
	}
</style>