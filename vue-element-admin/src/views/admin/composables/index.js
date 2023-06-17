import { reactive } from 'vue'
import { changePwd, list, remove } from '@/api/admin'
import { useRouter } from 'vue-router'
import { ElMessage, ElMessageBox } from 'element-plus'

export function useRepositories () {

  const listRes = reactive({
    list: [], total: 0, loading: false,
  })
  const listQuery = reactive({
    page: 1,
    page_size: 10,
    nickname: '',
  })

  const getList = async () => {
    listRes.loading = true
    const res = await list(listQuery).catch(_ => false)
    listRes.loading = false
    if (res) {
      listRes.list = res.data.list
      listRes.total = res.data.total
    }
  }
  const handlerQuery = () => {
    if (listQuery.page > 1) {
      listQuery.page = 1
    }
    getList()
  }
  return {
    listRes,
    listQuery,
    getList,
    handlerQuery,
  }
}

export function useActions (handlerQuery) {
  const del = async (row) => {
    const cf = await ElMessageBox.confirm('确定删除么?', { type: 'warning' }).catch(_ => false)
    if (!cf) {
      return false
    }

    const res = await remove({ id: row.id }).catch(_ => false)
    if (res && res.code === 0) {
      handlerQuery()
    }
  }

  const changePass = async (admin) => {
    const input = await ElMessageBox.prompt('请输入新密码', '重置密码').catch(_ => false)
    if (!input) {
      return
    }
    const confirm = await ElMessageBox.confirm('确定重置密码么？', { type: 'warning' }).catch(_ => false)
    if (!confirm) {
      return
    }
    const res = await changePwd({ id: admin.id, password: input.value }).catch(_ => false)
    if (!res) {
      return
    }
    ElMessage.success('修改成功')
    handlerQuery()
  }

  const router = useRouter()
  const toEdit = (row) => {
    router.push('/admin/edit/' + row.id)
  }
  const toAdd = () => {
    router.push('/admin/add')
  }

  return {
    del, changePass, toEdit, toAdd,
  }

}

