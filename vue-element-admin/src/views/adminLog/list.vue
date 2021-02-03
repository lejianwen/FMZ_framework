<template>
  <div class="app-container">
    <div class="filter-container">
      <listQueryForm
        :value.sync="listQuery"
        :form-options="listQueryFormOptions"
        @handleFilter="handleFilter"
      >
        <template slot="otherButton">
          <el-button @click="batchDelete">批量删除</el-button>
        </template>
      </listQueryForm>
    </div>
    <listTable
      :list-loading="listLoading"
      :data="list"
      :table-columns="tableColumns"
      :actions-update="false"
      :actions-detail="false"
      @handleDelete="handleDelete"
      @selectionChange="selectionChange"
    />
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.page_size" :page-sizes="[10, 20, 30, 50]" @pagination="getList" />
  </div>
</template>

<script>
import { fetchList, deleteItem, batchDelete } from '@/api/adminLog'
import listQueryForm from '@/components/basePage/listQueryForm'
import listTable from '@/components/basePage/listTable'
import pagination from '@/components/Pagination'

const model = 'adminLog'
export default {
  name: `${model}List`,
  components: { pagination, listQueryForm, listTable },
  data() {
    return {
      listQueryFormOptions: [
        { label: '管理员id', type: 'input', prop: 'admin_id' },
        { label: '路径', type: 'input', prop: 'uri' },
        { label: '方法', type: 'select', prop: 'method', options: [{ value: 'GET' }, { value: 'POST' }] }
      ],
      listLoading: true,
      total: 0,
      listQuery: {
        page: 1,
        page_size: 10,
        _order: { 'id': 'descending' }
      },
      list: [],
      tableColumns: [
        { label: '', prop: 'id', width: '50px', type: 'selection' },
        { label: 'ID', prop: 'id', width: '80px' },
        { label: '管理员ID', prop: 'admin_id', width: '80px' },
        { label: '路径', prop: 'uri' },
        { label: '方法', prop: 'method' },
        { label: 'GET参数', prop: 'query' },
        { label: 'POST数据', prop: 'post', formatter: row => JSON.stringify(row.post) },
        { label: 'IP', prop: 'ip' },
        { label: '时间', prop: 'created_at' }
        // { label: '备注', prop: 'remark' },

      ],
      selected: []
    }
  },
  created() {
    this.getList()
  },
  methods: {
    batchDelete() {
      if (!this.selected.length) {
        return false
      }
      this.$msgbox.confirm('确定删除么？', '删除', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warn'
      }).then(() => {
        const ids = JSON.stringify(this.selected.map(item => item.id))
        batchDelete({ ids }).then(res => {
          this.$message.success('操作成功')
          this.getList()
        })
      }, () => {
        console.log('取消')
      })
    },
    selectionChange(selected) {
      this.selected = selected
    },
    getList() {
      this.listLoading = true
      fetchList(this.listQuery).then(response => {
        this.list = response.data.list
        this.total = response.data.total
        this.listLoading = false
      })
    },
    handleDelete(row) {
      this.$msgbox.confirm('确定删除么？', '删除', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warn'
      }).then(() => {
        deleteItem({ id: row.id }).then(res => {
          this.$message.success('删除成功')
          this.getList()
        })
      }, () => {
        console.log('取消')
      })
    },
    handleFilter() {
      console.log('handleFilter', this.listQuery)
      this.getList()
    }
  }
}
</script>

<style scoped>

</style>
