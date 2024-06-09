@extends('Bhadhan::layouts.app')

@section('title', 'DB - SCHEMA')

@section('content')
    <div id="vue_app">
        <div class="tree container-fluid">
            <div class="col-12">
                <table class="table mt-1">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" colspan="2" class="text-center connection-name"><span
                                    v-text="'Your Current Connection Is : ' + schemas?.connection_name"></span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(table, tableKey) in schemas.tables">
                            <tr class="lh-08 db-table cursor-pointer" @click="toggleDetails(tableKey)">
                                <td scope="col"><strong>+</strong></td>
                                <td scope="col" class="font-weight-bold" v-html="'<strong>'+table.table_name+'</strong>'"></td>
                            </tr>
                            <tr v-if="currentTable === tableKey">
                                <td colspan="2">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr class="f-08">
                                                <th class="lh-08">Column Name</th>
                                                <th class="lh-08">Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="column in table.columns" :key="column.name" class="f-08">
                                                <td class="lh-06">
                                                    @{{ column.column_name }} : @{{ column?.udt_name }}
                                                    @{{ column.character_maximum_length ? '(' + column.character_maximum_length + ')' : '' }}
                                                </td>
                                                <td class="lh-06">
                                                    @{{ column.column_name == primaryColumn ? '(PRIMARY KEY🔑)' : '' }}
                                                    @{{ column.is_nullable == 'YES' ? '(NULL ✅)' : '(NOT_NULL ❌)' }}

                                                    <span v-if="column.column_default != null">
                                                        <span v-if="column.column_default == 'true'">DEFAULT VALUE :
                                                            BOOL(✅)</span>
                                                        <span v-if="column.column_default == 'false'">DEFAULT VALUE :
                                                            BOOL(❌)</span>
                                                        <span
                                                            v-if="column.column_default != 'false' && column.column_default != 'true' && !checkAutoIncrement(column.column_default)"
                                                            v-text="'(DEAFULT VALUE :' + column.column_default + ')'"></span>
                                                        <span
                                                            v-if="column.column_default != 'false' && column.column_default != 'true' && checkAutoIncrement(column.column_default)"
                                                            v-html="'(<i>AUTO_INCREEMENT</i>)'"></span>
                                                    </span>
                                                    <span v-html="foreignKey(column.column_name)"></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
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
                        return 'FOREIGN KEY REFERENCES <i>' +
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
