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
			<div class="search-results" v-if="searchResults.length!==0 && listItem!=''">
					<ul>
						<li v-for="(item, index) in searchResults"
							key:="item"
							v-bind:tabindex="index"
							v-bind:class="highlight(index)">
							<char-highlight
								v-bind:word="item"
								v-bind:matches="matchMatrix[index]">
							</char-highlight>
						</li>
					</ul>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">

	import charHighlight from './char_highlight.vue';
	export default {
		components: {
			charHighlight
		},
		data () {
			return {
				listItem: '',
				list: [],
				searchResults: [],
				selectedResult:0,
				matchMatrix: []
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
			}
		},
		methods: {
			highlight: function(index) {
				return index===this.selectedResult? "selected":"";
			},
			lookUp(e) {
				if(this.listItem==="") {
					return;
				}
				e.preventDefault();
				if (e.key==="ArrowDown" || e.key==="ArrowRight" || e.key==="Tab") {
					this.selectedResult =Math.min((this.selectedResult +1)%3, this.searchResults.length-1);
					console.log('selecged: ', this.selectedResult);
					return;
				}
				if (e.key==="ArrowUp" || e.key==="ArrowLeft") {
					this.selectedResult =Math.min(Math.max((this.selectedResult -1)%3, 0), this.searchResults.length);
					console.log('selecged: ', this.selectedResult);
					return;
				} else {
					var results = this.fuzzySearch(this.listItem, this.$_.pluck(this.availableItems, "name"));

					this.selectedResult = 0;
					this.searchResults=[];
					this.matchMatrix=[];
					if (results === undefined) {
						return;
					}
					results = results.slice(0,3);
					for (var i=0; i<results.length;i++) {
						this.searchResults.push(results[i][1]);
						this.matchMatrix.push(results[i][2]);
					}
				}
			},
			addItem() {
				if (this.searchResults.length>0) {
					this.listItem = this.searchResults[this.selectedResult];
				}
				var item = this.$_.findWhere(this.availableItems, {name: this.listItem})
				if (item === undefined) {
					item = {name:this.listItem};
					// todo: einheit und Menge abfragen
				}
				// item not already added? -> add
				if (this.$_.findWhere(this.list, {name: this.listItem}) === undefined) {
					this.list.push(item);
				}
				// todo: remove duplicates.
				this.listItem = '';
				this.$emit('update-list', this.list);
				this.searchResults = [];
			},

			fuzzySearch: function(searchstring, possibleResults) {
				var scores = [];
				var matches = [];
				var masks =[];
				var self = this;
				searchstring = searchstring.toLowerCase();
				this.$_.each(possibleResults, function(result, index) {
					masks[index]=new Array(result.length).fill(0);
				});
				// build match matrix
				this.$_.each(possibleResults, function(result, index) {
					var ind = 0;
					result = result.toLowerCase();
					for (var i=0; i< searchstring.length;i++) {
						var c = searchstring[i];
						// found character
						if (result.indexOf(c, ind)!==-1) {
							ind = result.indexOf(c, ind);
							masks[index][ind] =1;
							ind++;
						} else {
							// does not match anymore, remove matches
							masks[index].fill(0);
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
					} else {

						var streaks = matchStreaks(matrix);
						var score = 0;
						self.$_.each(streaks, function(streak) {
							score += streak;
						});
						score += self.$_.max(streaks);
						scores.push(score);
					}
				});
				if (this.$_.max(scores)<=0) {
					return undefined;
				}
				// todo sort scores and save indizes
				// than sort items by this index
				var ranking = this.$_.zip(scores, possibleResults, masks);
				ranking = this.$_.filter(ranking, function(item) {return item[0]>0});
				// todo return only the sorted
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
	  background: #eee;
	}
</style>