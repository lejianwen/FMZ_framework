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
    if (listQuery.page === 1) {
      getList()
    } else {
      listQuery.page = 1
      //由watch 触发
    }
  }

  return {
    listRes,
    listQuery,
    handlerQuery,
    getList,
  }
}

export function useToEditOrAdd () {
  const router = useRouter()
  const toEdit = (row) => {
    router.push('/admin/edit/' + row.id)
  }
  const toAdd = () => {
    router.push('/admin/add')
  }

  return {
    toAdd,
    toEdit,
  }
}

export function actions(){

  const del = async (id) => {
    const cf = await ElMessageBox.confirm('确定删除么?', {
      confirmButtonText: '确定',
      cancelButtonText: '取消',
      type: 'warning',
    }).catch(_ => false)
    if (!cf) {
      return false
    }

    const res = remove({ id }).catch(_ => false)
    return res
  }

  const changePass = async (admin) => {
    const input = await ElMessageBox.prompt('请输入新密码', '重置密码', {
      confirmButtonText: '确定',
      cancelButtonText: '取消',
    }).catch(_ => false)
    if (!input) {
      return
    }
    const confirm = await ElMessageBox.confirm('确定重置密码么？', {
      confirmButtonText: '确定',
      cancelButtonText: '取消',
    }).catch(_ => false)
    if (!confirm) {
      return
    }
    const res = await changePwd({ id: admin.id, password: input.value }).catch(_ => false)
    if (!res) {
      return
    }
    ElMessage.success('修改成功')
  }

  return {
    del,changePass
  }


}

