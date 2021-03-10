<script>
  export default {
    props: {
      url: {
        type: String,
        required: true
      },
      move: {
        type: String,
        required: true
      }
    },
    data () {
      return {
        loading: false,
        error: null,
        name: '',
        redirect: '',
        description: '',
        project_manager: ''
      }
    },
    methods: {
      create () {
        if (this.filled) {
          axios.post(this.url, this.$data)
            .then(r => {
              window.location.href = this.move
              this.loading = false
            })
            .catch(e => {
              this.error = e
              this.loading = false
            })
        }
      }
    },
    computed: {
      filled () {
        return this.name && this.redirect
      }
    }
  }
</script>
