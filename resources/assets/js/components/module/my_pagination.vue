<template>
  <div class="md-table-pagination">
	<span class="md-table-pagination-label">{{ mdLabel }}:</span>

	<md-select v-model="currentSize" md-menu-class="md-pagination-select" @change="changeSize" v-if="mdPageOptions">
		<md-option v-for="amount in mdPageOptions" :value="amount">{{ amount }}</md-option>
	</md-select>

	<span>{{ (currentPage - 1) * currentSize + 1 }}-{{ subTotal }} {{ mdSeparator }} {{ mdTotal }}</span>

	<!--<router-link tag="button"-->
				 <!--class="md-button md-icon-button md-table-pagination-previous"-->
				 <!--:to="previous"-->
				 <!--@click.native="previousPage"-->
				 <!--:disabled="canPrevious">-->
	  <!--<md-icon>keyboard_arrow_left</md-icon>-->
	<!--</router-link>-->

	<!--<router-link tag="button"-->
				 <!--class="md-button md-icon-button md-table-pagination-previous"-->
				 <!--:to="next"-->
				 <!--@click.native="nextPage"-->
				 <!--:disabled="canNext">-->
	  <!--<md-icon>keyboard_arrow_right</md-icon>-->
	<!--</router-link>-->

	<button class="md-button md-icon-button md-table-pagination-previous"
			@click="previousPage"
			:disabled="canPrevious">
	  <!--<md-ink-ripple :md-disabled="canPrevious"></md-ink-ripple>-->
	  <md-icon>keyboard_arrow_left</md-icon>
	</button>

	<button class="md-button md-icon-button md-table-pagination-previous"
			@click="nextPage"
			:disabled="canNext">
	  <!--<md-ink-ripple :md-disabled="canPrevious"></md-ink-ripple>-->
	  <md-icon>keyboard_arrow_right</md-icon>
	</button>

  </div>
</template>

<script type="es6">
  export default {
    props: {
      mdCount: {
		type: [Number, String],
		default: 10
	  },
      mdSize: {
        type: [Number, String],
        default: 10
      },
      mdPageOptions: [Array, Boolean],
      mdPage: {
        type: [Number, String],
        default: 1
      },
      mdTotal: {
        type: [Number, String],
        default: 'Many'
      },
      mdLabel: {
        type: String,
        default: 'Rows per page'
      },
      mdSeparator: {
        type: String,
        default: 'of'
      }
    },

    data() {
      return {
        subTotal: 0,
		currentCount: parseInt(this.mdCount, 10),
		currentSize: parseInt(this.mdSize, 10),
		currentPage: parseInt(this.mdPage, 10),
		canFireEvents: false
	  };
    },

    computed: {
	  totalItems() {
		return isNaN(this.mdTotal) ?
				Number.MAX_SAFE_INTEGER : parseInt(this.mdTotal, 10)
	  },
	  canPrevious() {
		return (this.currentPage === 1)
	  },
	  canNext() {
		return (this.currentSize * this.currentPage >= this.totalItems)
	  }
    },

	watch: {
	  mdPage(currentPage) {
	    // console.log(currentPage)
	    this.currentPage = currentPage
	  },

	  mdTotal(total) {
		this.subTotal = this.currentPage * this.currentSize > total ?
				total : this.currentPage * this.currentSize
	  }
	},

    methods: {
      emitPaginationEvent() {
        if (this.canFireEvents) {
          const sub = this.currentPage * this.currentSize;

		  this.subTotal = sub > this.mdTotal ? this.mdTotal : sub;
		  // console.log(`${sub} ${this.mdTotal}`)
		  // console.log(`${this.currentSize} ${this.currentPage}`)

          this.$emit('pagination', {
            size: this.currentSize,
            page: this.currentPage
          });
        }
      },

      changeSize() {
        if (this.canFireEvents) {
          this.$emit('size', this.currentSize);
          this.emitPaginationEvent();
        }
      },

      previousPage() {
        if (this.canFireEvents) {
          this.currentPage--;
          this.$emit('page', this.currentPage);
          this.emitPaginationEvent();
        }
	  },

      nextPage() {
		if (this.canFireEvents) {
          this.currentPage++;
          this.$emit('page', this.currentPage);
          this.emitPaginationEvent();
        }
	  }
    },

    mounted() {
      this.$nextTick(() => {
        this.subTotal = this.currentPage * this.currentSize > this.mdTotal ?
				this.currentCount : this.currentPage * this.mdTotal
        this.mdPageOptions = this.mdPageOptions || [10, 25, 50, 100]
        this.currentSize = this.mdPageOptions[0]
        this.canFireEvents = true
      })
    }
  };
</script>
