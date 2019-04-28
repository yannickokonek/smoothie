<template>
	<div>
		<input 
			:placeholder="defaultText"
			v-model="listItem"
			v-on:keyup.enter="addItem">
		</input>
		<ul>
			<li v-for="item in list"
				v-on:click="removeItem(item)"
				key:="item"
			>{{display(item)}}
			</li>
		</ul>
	</div>
</template>
<script type="text/javascript">

	export default {
		data () {
			return {
				listItem: '',
				list: []
			}
		},
		props: {
			defaultText: {
				type: String,
				required: true
			},
			availableItems: {
				type: Array,
				required: false,
				default: function() {return [];}
			},
			display: {
				type: Function,
				required: false,
				default: function(item) {
					return item;
				},
			}
		},
		methods: {
			removeItem(item) {     
				this.list = this.$_.filter(this.list, function(f){return f.name != item.name});
				this.$emit('update-list', this.list);
			},
			addItem() {
				var item = this.$_.findWhere(this.availableItems, {name: this.listItem}) || {name:this.listItem}; 
				// todo: if no item is found, add it just like this.
				this.list.push(item);
				this.list = this.$_.unique(this.list);
				this.listItem = '';
				this.$emit('update-list', this.list);
			},
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