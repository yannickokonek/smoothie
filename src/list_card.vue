<template>
    <ul>
        <li v-for="item in list"
        	v-on:click="$emit('item-event', item)"
        >{{display(item)}}                             
        </li>
    </ul>
</template>
<script type="text/javascript">

	export default {
		data () {
			return {
				// the data to use in this template (aside from properties)
			}
		},
		props: {
			list: {
				type: Array,
				required: true,
			},
			display: {
				type: Function,
				required: false,
				default: function(item) {
					return item;
				},
			}
		}
	}
</script>
<style type="text/css">
	li {
	    background-color: white;
        list-style-type: disc;
        cursor: pointer;
	}
</style>