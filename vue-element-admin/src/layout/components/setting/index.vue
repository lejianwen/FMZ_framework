<template>
  <div class="setting">
    <el-dropdown class="menu-item">
      <div class="title">
        <el-image class="avatar" :src="user.avatar"></el-image>
        <span class="nickname">{{user.nickname}}</span>
        <el-icon>
          <el-icon-arrow-down/>
        </el-icon>
      </div>

      <template #dropdown>
        <el-dropdown-menu>
          <el-dropdown-item @click="logout">退出登录</el-dropdown-item>
        </el-dropdown-menu>
      </template>
    </el-dropdown>
  </div>
</template>

<script>
  import { defineComponent, reactive } from 'vue'
  import { useUserStore } from '@/store/user'

  export default defineComponent({
    name: 'Setting',
    components: {},
    setup () {
      const userStore = useUserStore()
      const user = userStore

      const logout = () => {
        userStore.logout()
        window.location.reload()
      }
      return {
        user,
        logout,
      }
    },
  })
</script>

<style lang="scss" scoped>
  .setting {
    margin-left: auto;
    display: flex;
    align-items: center;
    justify-content: space-around;

    .title {
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: space-around;

      .nickname {
        padding: 0 10px;
      }
    }
  }
</style>
