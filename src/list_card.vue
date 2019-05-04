<template>
	<div>
		<div class="input-container">
			<input class="input"
				:placeholder="defaultText"
				v-model="listItem"
				v-on:keyup.enter="addItem"
				v-on:keyup="lookUp"
				>
			</input>
			<div class="search-results">
					<ul>
						<li v-for="(item, index) in searchResults"
							key:="item"
							v-bind:tabindex="index">
							{{item}}
						</li>
					</ul>
			</div>
		</div>
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
				list: [],
				searchResults: ["Banane", "Erdbeere", "Melone"]
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
			lookUp() {
				if(this.listItem==="") {
					return;
				}
				var results = this.fuzzySearch(this.listItem, this.availableItems);
				console.log("results: ", results);
			},
			addItem() {
				var item = this.$_.findWhere(this.availableItems, {name: this.listItem}) || {name:this.listItem};
				this.list.push(item);
				// todo: remove duplicates.
				this.listItem = '';
				this.$emit('update-list', this.list);
			},

			fuzzySearch: function(searchstring, possibleResults) {
				var scores = [];
				var matches = [];
				var masks =[];
				var self = this;
				this.$_.each(possibleResults, function(result, index) {
					masks[index]=new Array(result.name.length).fill(0);
				});
				// build match matrix
				this.$_.each(possibleResults, function(result, index) {
					var ind = 0;
					for (var i=0; i< searchstring.length;i++) {
						var c = searchstring[i];
						// found character
						if (result.name.indexOf(c, ind)!==-1) {
							ind = result.name.indexOf(c, ind);
							masks[index][ind] =1;
						} else {
							masks[index] = [];
							break;
						}
					}
				});
				// find continues matches
				var matchStreaks = function(matrix) {
					var streaks = [];
					var isStreak = false;
					var position = 0;
					for (var i=0; i<matrix.length; i++) {
						switch(isStreak) {
							case true:
								if (matrix[i]===1) {
									streaks[position] ++;
								} else {
									isStreak = false;
								}
							case false:
								if (matrix[i]===1) {
									streaks.push(1);
									isStreak = true;
								}
						}
					}
					return streaks;
				};

				this.$_.each(masks, function(matrix, index) {
					if (matrix.length===0) {
						scores.push(-1);
					}
					var streaks = matchStreaks(matrix);
					var score = 0;
					self.$_.each(streaks, function(streak) {
						score += streak;
					});
					score += self.$_.max(streaks);
					scores.push(score);
				});
				if (this.$_.max(scores)<=0) {
					return undefined;
				}
				var ranking = this.$_.zip(possibleResults, scores);
				ranking = this.$_.filter(ranking, function(item) {return item[1]>0});
				debugger;
				return this.$_.sortBy(ranking, function(item) {return -item[1]});
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
	.input-container {
		position: relative;
	}
	.input {
		width: 100%;
	}
	.search-results {
		opacity: .8;
		position: absolute;
		top: 30px;
		left: 0;
		width: 100%;
		height: 100px;
		background: white;
		color: grey;
		padding: 5px;
		z-index: 3;
	}
	.search-results li {
		background-color: transparent;
		list-style: none;
	}
	.search-results li:focus {
	  background: #eee;
	}
</style>