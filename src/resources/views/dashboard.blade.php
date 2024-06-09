@extends('Bhadhan::layouts.app')

@section('title', 'DB - SCHEMA')

@section('content')

    <div style="height: 90vh; width: 95vw; display: flex; justify-content: center; align-items: center; overflow: hidden;">
        <div class="text-white">
            <h1>Hello there! <span class="wave">👋</span></h1> <br>
            <p class="animated-text">
                <span>W</span><span>e</span><span>l</span><span>c</span><span>o</span><span>m</span><span>e</span>
                <span>t</span><span>o</span>
                <span>B</span><span>H</span><span>A</span><span>D</span><span>H</span><span>A</span><span>N</span>
                <span>D</span><span>B</span>
                <span>M</span><span>A</span><span>N</span><span>A</span><span>G</span><span>E</span><span>R</span>
                <span>. W</span><span>e</span><span>'</span><span>r</span><span>e</span>
                <span>g</span><span>l</span><span>a</span><span>d</span>
                <span>t</span><span>o</span>
                <span>h</span><span>a</span><span>v</span><span>e</span>
                <span>y</span><span>o</span><span>u</span>
                <span>h</span><span>e</span><span>r</span><span>e!</span>
                <span>😊</span><span class="bounce">🎉</span><span class="bounce">🚀</span>
            </p>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        new Vue({
            el: "#vue_app",
            data: {
                schemas: [],
                currentTable: null,
                primaryColumn: null,
                currentTableName: null,
                foreignKeys: []
            },
            methods: {
                loadSchema: function() {
                    let vm = this;
                    axios.get("{{ route('bhadhan-db-manager.schema') }}", {
                        params: {
                            isAjax: true
                        }
                    }).then(function(res) {
                        vm.schemas = res.data;
                    }).catch(function(err) {
                        console.log(err);
                    })
                },
                loadDetails: function(tableKey) {
                    let vm = this;
                    axios.get("{{ route('bhadhan-db-manager.schema') }}", {
                        params: {
                            isAjax: true,
                            tableName: vm.schemas.tables[tableKey].table_name,
                        }
                    }).then(function(res) {
                        vm.$set(vm.schemas.tables[tableKey], 'columns', res.data[vm.schemas.tables[
                            tableKey].table_name]);
                        vm.currentTableName = vm.schemas?.tables[tableKey]?.table_name;
                        vm.primaryColumn = res.data.primary_key[0].column_name;
                        vm.foreignKeys = res.data.foreign_keys;
                    }).catch(function(err) {
                        console.log(err);
                    })
                },
                toggleDetails: function(tableKey) {
                    if (this.currentTable === tableKey) {
                        this.currentTable = null;
                    } else {
                        this.currentTable = tableKey;
                        if (!this.schemas.tables[tableKey].columns) {
                            this.loadDetails(tableKey);
                        }
                    }
                },
                checkAutoIncrement: function(columnDefault) {
                    let vm = this;
                    return columnDefault == "nextval('" + vm.currentTableName + "_id_seq'::regclass)";
                },
                foreignKey: function(columnName) {
                    let vm = this;
                    foreignKey = vm.foreignKeys.find(key => key.column_name === columnName);
                    if (foreignKey) {
                        return 'FOREIGN KEY ( <i>' + foreignKey.column_name + '</i>) REFERENCES <i>' +
                            foreignKey.foreign_table_name + ' </i>WITH CONSTRAINED : <i>' + foreignKey
                            .constraint_name + '</i>';
                    }
                    return null;
                }
            },
            mounted() {
                let vm = this;
                vm.loadSchema();
            }
        });
    </script>
@endsection
