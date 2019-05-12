<template>
	<ul>
		<li v-for="item in list"
			v-on:click="$emit('remove-item', item)"
			key:="item"
		>{{display(item)}} <button class="btn btn-default"><i>remove-icon</i></button>
		</li>
	</ul>
</template>
<script type="text/javascript">

	import charHighlight from './char_highlight.vue';
	import search from './search.vue';
	export default {
		components: {
			charHighlight,
			search,
		},
		props: {
			list: {
				type: Array,
				required: true
			},
			display: {
				type: Function,
				required: false,
				default: function(item) {
					return item;
				},
			},
		},
	}
</script>
<style type="text/css">
	li {
		background-color: white;
		list-style-type: disc;
		cursor: pointer;
	}
	.input-container {
		position: relative;
	}
	.input {
		width: 100%;
	}
	.search-results {
		/*opacity: .3;*/
		position: absolute;
		top: 30px;
		left: 0;
		width: 100%;
		height: 100px;
		background: white;
		color: black;
		padding: 5px;
		z-index: 3;
	}
	.search-results li {
		background-color: transparent;
		list-style: none;
	}
	.search-results li.selected {
	  background: #ccc;
	}
</style>