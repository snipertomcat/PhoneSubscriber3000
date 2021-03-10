<script>

    export default {
        name: 'apithy-import-users',
        components: {
            'apithy-xls-csv-parser': () => import('./Apithy-Xls-Csv-Parser/index'),
        },
        props: {
            company_id: null,
            companies:null
        },
        computed: {
            paginatedResults: function () {
                if(this.usersInfo){
                    return this.usersInfo.filter((row, index) => {
                        let start = (this.currentPage - 1) * this.pageSize;
                        let end = this.currentPage * this.pageSize;
                        if (index >= start && index < end) return true;
                    });
                }else{
                    return [];
                }
            }
        },
        methods: {
            onValidate(results) {
                this.isLoading = true;
                this.results = results;
                this.usersInfo = {};

                let vm = this;

                this.results.forEach(function (value) {
                    let field = value.column;
                    value.data.forEach(function (fieldValue, index) {
                        if (typeof  vm.usersInfo[index] == 'undefined') {
                            vm.usersInfo[index] = {};
                        }
                        vm.usersInfo[index][field] = fieldValue;
                    });
                });

                this.sendData(this.usersInfo);
            }
            ,
            showError() {

            },
            clearData(){
                this.usersInfo=null;
                this.results=[];
            },
            sendData(usersInfo) {
                let vm = this;
                let data;

                data ={"users": vm.usersInfo};

                if(this.company){
                    data.company_id = this.company;
                }

                vm.usersInfo = {};
                axios.post('/users/import-json', data)
                    .then(function (response) {
                        setTimeout(function () {
                            vm.$nextTick(function () {

                                vm.columns.push({name: 'Importado', value: 'imported'});
                                vm.messageData.visible = true;
                                vm.messageData.content = 'Proceso finalizado';
                                vm.messageData.header = response.data.message;
                                vm.messageData.icon = 'check circle';
                                vm.messageData.messageClass = {success: true}


                                if (response.data.errors.length) {
                                    vm.messageData.content = 'Existen errores en el archivo a importar, revisa los datos';
                                    vm.messageData.messageClass = {error: true, success: false}
                                    vm.messageData.icon = 'times circle';
                                }

                                vm.usersInfo = response.data.data;
                                vm.isLoading = false;

                            })
                        }, 1000)


                    })
                    .catch(function (error) {
                        vm.isLoading = false;
                    });
            },
            resultNextPage: function () {
                if ((this.currentPage * this.pageSize) < this.usersInfo.length) this.currentPage++;
            },
            resultPrevPage: function () {
                if (this.currentPage > 1) this.currentPage--;
            }
        }
        ,
        data() {
            return {
                columns: [
                    {name: 'Email', value: 'email'},
                    {name: 'Nombre', value: 'name'},
                    {name: 'Apellido', value: 'surname'},
                    {name: 'Rol', value: 'role', isOptional: true},
                    {name: 'Puesto', value: 'job_title', isOptional: true},
                    {name: 'Area', value: 'job_area', isOptional: true},
                ],
                results: null,
                help: 'Columnas obligatorias: Email, Nombre, Apellidos',
                isLoading: false,
                usersInfo: [],
                pageSize: 10,
                currentPage: 1,
                company:this.company_id
            };
        },
    }
    ;
</script>