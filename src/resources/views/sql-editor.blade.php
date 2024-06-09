@extends('Bhadhan::layouts.app')

@section('title', 'DB - SCHEMA | SQL Editor')

@section('content')
    <link href="{{ asset('vendor/bidhan/bhadhan/css/prism-tomorrow.min.css') }}" rel="stylesheet" />
    <div id="vue_app">
        <div class="container-fluid">
            <div class="col-12">
                <div class="form-group">
                    <p class="sql-label mt-1 mb-0">Enter Your SQL Query :</p>
                    <div id="editor" class="editor" contenteditable="true" @input="updateRawSql" @keydown="handleKeydown"
                        ref="editor"></div>
                </div>
            </div>
            <div :class="sqlData.length ? 'sql-table-div col-12' : 'col-12'" v-if="sqlData">
                <table class="table mt-1 table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <template v-for="(columnName, columnNameKey) in columnNames">
                                <th class="f-08" v-text="columnName"></th>
                            </template>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="f-08 text-white" v-for="(row, rowKey) in sqlData">
                            <template v-for="(columnValue, columnValueKey) in columnNames">
                                <td class="f-08" v-html="row[columnValue] ? row[columnValue] : '<i>' + null + '</i>'">
                                </td>
                            </template>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-12 text-center">
                <p class="sql-label mt-1 mb-0 text-danger-imp" v-text="errMessage">
                </p>
            </div>
            <div class="row" v-if="messageSummary">
                <div class="col-12 mt-0 text-center">
                    <span v-text="messageSummary" class="text-center text-white"></span>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('vendor/bidhan/bhadhan/js/prism.min.js') }}"></script>
    <script src="{{ asset('vendor/bidhan/bhadhan/js/prism-sql.min.js') }}"></script>
    <script>
        new Vue({
            el: "#vue_app",
            data: {
                rawSql: "",
                columnNames: [],
                sqlData: [],
                messageSummary: null,
                errMessage: null
            },
            methods: {
                getCaretPosition: function(element) {
                    let caretOffset = 0;
                    const doc = element.ownerDocument || element.document;
                    const win = doc.defaultView || doc.parentWindow;
                    const sel = win.getSelection();
                    if (sel.rangeCount > 0) {
                        const range = sel.getRangeAt(0);
                        const preCaretRange = range.cloneRange();
                        preCaretRange.selectNodeContents(element);
                        preCaretRange.setEnd(range.startContainer, range.startOffset);
                        caretOffset = preCaretRange.toString().length;
                    }
                    return caretOffset;
                },
                setCaretPosition: function(element, offset) {
                    const range = document.createRange();
                    const sel = window.getSelection();
                    const nodeStack = [element];
                    let node, charCount = 0;
                    let foundStart = false;

                    while (node = nodeStack.pop()) {
                        if (node.nodeType === 3) {
                            const nextCharCount = charCount + node.length;
                            if (!foundStart && offset >= charCount && offset <= nextCharCount) {
                                range.setStart(node, offset - charCount);
                                foundStart = true;
                            }
                            charCount = nextCharCount;
                        } else {
                            let i = node.childNodes.length;
                            while (i--) {
                                nodeStack.push(node.childNodes[i]);
                            }
                        }
                    }
                    range.collapse(true);
                    sel.removeAllRanges();
                    sel.addRange(range);
                },
                updateRawSql: function() {
                    const editor = this.$refs.editor;
                    const caretPosition = this.getCaretPosition(editor);

                    this.rawSql = editor.innerText;
                    this.$nextTick(() => {
                        const highlighted = Prism.highlight(this.rawSql, Prism.languages.sql, 'sql');
                        editor.innerHTML = highlighted;
                        this.setCaretPosition(editor, caretPosition);
                    });
                },
                handleKeydown: function(event) {
                    let vm = this;
                    if (event.ctrlKey && event.key === 'Enter') {
                        event.preventDefault();
                        vm.submitQuery();
                    }
                },
                submitQuery: function() {
                    let vm = this;
                    axios.post("{{ route('bhadhan-db-manager.sqlToData') }}", {
                        rawSql: vm.rawSql
                    }).then(function(res) {
                        vm.columnNames = res.data.columnNames;
                        vm.sqlData = res.data.fetchFromSql;
                        vm.messageSummary = res.data.summary;
                        vm.errMessage = null;
                    }).catch(function(err) {
                        vm.columnNames = [];
                        vm.sqlData = [];
                        vm.messageSummary = null;
                        vm.errMessage = err.response.data;
                    });
                }
            },
            mounted() {
                this.$nextTick(function() {
                    Prism.highlightAll();
                });
            }
        });
    </script>
@endsection
