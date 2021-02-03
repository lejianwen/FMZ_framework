<template>
  <div class="app-container">
    <div class="filter-container">
      <listQueryForm
        :value.sync="listQuery"
        :form-options="listQueryFormOptions"
        @handleFilter="handleFilter"
        @handleCreate="handleCreate"
      />
    </div>
    <listTable
      :list-loading="listLoading"
      :data="list"
      :table-columns="tableColumns"
      :merge-actions="mergeActions"
      @handleButtonClick="handleChangeTest"
      @handleSelectChange="handleChangeTest"
      @handleUpdate="handleUpdate"
      @handleDetail="handleDetail"
      @handleDelete="handleDelete"
      @sortChange="sortChange"
    >
      <template slot="beforeActionsColumn">
        <el-table-column
          header-align="center"
          prop="name"
          label="beforeActionsColumn"
        >
          <template slot-scope="{row}">
            这个是名字 {{ row.name }}
          </template>
        </el-table-column>
        <el-table-column
          header-align="center"
          prop="name"
          label="beforeActionsColumn"
        >
          <template slot-scope="{row}">
            这个是名字 {{ row.name }}
          </template>
        </el-table-column>
      </template>
      <el-table-column
        slot="lastColumn"
        prop="name"
        label="columnLabel"
      >
        <template slot-scope="{row}">
          这个是名字 {{ row.name }}
        </template>
      </el-table-column>

    </listTable>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.page_size" :page-sizes="[1, 2, 3, 5]" @pagination="getList" />

  </div>
</template>

<script>
import listQueryForm from '@/components/basePage/listQueryForm'
import listTable from '@/components/basePage/listTable'
import pagination from '@/components/Pagination'

export default {
  name: 'ExampleTest',
  components: { pagination, listQueryForm, listTable },
  data() {
    return {
      dialogFromStatus: '',
      dialogFormVisible: false,
      tableKey: 0,
      listLoading: true,
      total: 4,
      listQuery: {
        page: 1,
        page_size: 1,
        order: {
          prop: '',
          type: ''
        }
      },
      listQueryFormOptions: [
        { label: '年龄', type: 'input', prop: 'age' },
        { label: '出生日期', type: 'date', prop: 'birthday' },
        { label: '身份证号', type: 'number', prop: 'certNumber' },
        { label: '名字', type: 'input', prop: 'name' },
        { label: '名字', type: 'input', prop: 'name' }
      ],
      tableColumns: [
        { label: 'ID', prop: 'id', type: 'selection' },
        { label: '姓名', prop: 'name' },
        { label: '姓名tag', prop: 'name', type: 'tag', sortable: true },
        {
          label: '状态',
          prop: 'status',
          type: 'tag',
          options: [
            { value: 0, label: '删除', type: 'danger' },
            { value: 1, label: '禁用', type: 'info' },
            { value: -1, label: '启用', type: 'success' }
          ]
        },
        // { label: '子对象姓名', prop: 'childObj.name' },
        // { label: '子列表姓名', prop: 'childArr.0.name' },
        { label: '婚姻状况', prop: 'maritalStatus', type: 'enum', options: { 1: '未婚', 2: '已婚' }},
        // {
        //   label: '婚姻状况2',
        //   prop: 'maritalStatus',
        //   type: 'select',
        //   options: [{ label: '换1', value: 1 }, { label: '换2', value: 2 }],
        //   disabled: true
        // },

        { label: '按钮', prop: '', type: 'button', options: { type: 'danger', label: '危险按钮' }},
        { label: '测试html', prop: 'html', type: 'html' },
        { label: '测试图片', prop: 'image', type: 'img' }
      ],
      mergeActions: [
        // { label: '操作1', type: 'warning', prop: 'id' },
        // { label: '操作2', type: 'primary', prop: 'name' }
      ],
      list: [
        {
          id: 1,
          name: '测试者1号',
          status: 1,
          maritalStatus: 1,
          childObj: { name: 'c_name' },
          childArr: [{ name: 1 }],
          html: '<div style=\'color:red\'>teshidiv</div>',
          image: 'http://testxpt.xiaoputao.com/upload/common/201905/20190527/5601149267aca5065a49fd3abc525058.png'
        },
        {
          id: 2,
          name: '测试者2号',
          status: 0,
          maritalStatus: 2,
          childObj: { name: 'b_name' },
          childArr: [{ name: 2 }],
          html: '<div style=\'color:green\'>teshidiv</div>',
          image: 'http://testxpt.xiaoputao.com/upload/common/201905/20190527/5601149267aca5065a49fd3abc525058.png'
        },
        {
          id: 3,
          name: '测试者3号',
          status: -1,
          maritalStatus: 2,
          childObj: { name: 'a_name' },
          childArr: [{ name: 1 }, { name: 2 }],
          title: 'bbb',
          obc: { ab: 'abc', edf: 'edd' },
          image: 'http://testxpt.xiaoputao.com/upload/common/201905/20190527/5601149267aca5065a49fd3abc525058.png'
        }
      ]
    }
  },
  created() {
    this.getList()
  },
  methods: {
    sortChange({ column, prop, order }) {
      this.listQuery.order['prop'] = prop
      this.listQuery.order['type'] = order
      this.getList()
      console.log({ column, prop, order })
    },
    handleChangeTest(val) {
      console.log(val)
    },
    handleCreate() {
      console.log('create')
    },
    getList() {
      this.listLoading = true
      console.log('getList', this.listQuery)
      setTimeout(() => {
        this.listLoading = false
      }, 500)
    },
    handleUpdate(row) {
      console.log('handleUpdate', row)
    },
    handleDetail(row) {
      console.log('handleDetail', row)
    },
    handleDelete(row) {
      console.log('handleDelete', row)
    },
    handleFilter() {
      console.log('handleFilter', this.listQuery)
    }
  }
}
</script>

<style scoped>

</style>
