<template>

<div>
	<span v-for="(char, index) in word"
	key:="char" v-html="printChar(char, index)"></span>
</div>
</template>
<script type="text/javascript">

	export default {
		data () {
			return {}
		},
		props: {
			word: {
				type: String,
				required: true,
			},
			matches: {
				type: Array,
				required: true
			}
		},
		methods: {
			printChar: function(char, index) {
				return this.matches[index] === 1? "<b>" + char + "</b>": char;
			}
		}
	}
</script>
<style type="text/css">
</style>