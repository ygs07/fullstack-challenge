
import { defineStore } from "pinia";
import axios from "axios";

export const useUsersStore = defineStore('users', {
  state: () => ({
    count: 0,
    users: [],
    user: {} ,
    // currentPage: 1,
    // totalPages: 0,
    isLoading: true,
    isFirstVisit: true,


  }),
  getters: {
    doubleCount: (state) => state.count * 2,
  },
  actions: {
    increment() {
      this.count++
    },
      getUsers(page = 1) {
        axios.get(`http://localhost:8080/users`).then((response) => {
          this.users = response.data.users
          // this.currentPage = response.data.current_page
          // this.totalPages = response.data.last_page
          this.isFirstVisit = response.data.isFirstVisit
          this.isLoading = false
        });
    },
      getUser(userId) {
        axios.get(`http://localhost:8080/users/${userId}`).then((response) => {
          this.user = response.data.user
          // this.currentPage = response.data.current_page
          // this.totalPages = response.data.last_page
          this.isFirstVisit = response.data.isFirstVisit
          this.isLoading = false
        });
    },
  },

})


