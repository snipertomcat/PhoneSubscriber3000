<script>
  const emailRE = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  export default {
    props: {
      roles: {
        type: Array,
        required: true
      },
       companies: {
            type: Array,
            required: false
        },
    },
    data () {
      return {
        role: null,
        company: null,
        email: '',
        toDelete: null,
        loadStatus: false,
      }
    },
    mounted () {
      this.role = _.head(this.roles)['id']
      this.company = _.head(this.companies)['system_id']
    },
    computed: {
      enable () {
          console.log(this.email && emailRE.test(this.email))
        return this.email && emailRE.test(this.email);
      }
    },
    methods: {
      add () {
        if (!this.enable) return;
      },
      markToDelete (id) {
        this.toDelete = id;
      },
      send (id) {
        axios.get(`/users/invitations/resend/${id}`)
          .then(r => {
              this.messageData.visible=true;
              this.messageData.content='Invitación generado y enviada éxitosamente';
              this.messageData.header='Operación éxitosa';
              this.messageData.icon='check circle';
              this.messageData.messageClass= {success:true}
          })
          .catch(e => {
              this.messageData.visible=true;
              this.messageData.content='EL proceso de creación y envio de invitación ha fallado';
              this.messageData.header='Operación fallida';
              this.messageData.icon='times circle';
              this.messageData.messageClass= {negative:true}
          })
      },
    }
  }
</script>
