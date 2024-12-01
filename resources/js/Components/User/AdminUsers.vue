<template>
  <div class="frame-container min-h-[100vh]">
      <div class="flex justify-between px-4 mb-4">
          <button
              type="button"
              data-twe-toggle="modal"
              data-twe-target="#new_user"
              data-twe-ripple-init
              data-twe-ripple-color="light"
              @click="updateUser()"
              class="rounded bg-[#303030] px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal text-white hover:shadow-primary-2 focus:outline-none focus:ring-0"
          >
              Create
          </button>
          <SearchBox :search-handler="searchHandler" />
      </div>
      <div class="flex flex-col bg-white px-4 pt-4 pb-12 rounded-md">
          <div class="">
              <div class="">
                  <div class="flex items-center mb-4">
                      <label for="itemsPerPage" class="mr-2 text-gray-700"
                          >Show</label
                      >
                      <select
                          id="itemsPerPage"
                          @change="getUsers(true)"
                          v-model="per_page"
                          class="bg-white border-b border-gray-300 px-3 py-1 text-gray-700 focus:outline-none focus:ring-0 focus:border-indigo-500"
                      >
                          <option value="50">50</option>
                          <option value="100">100</option>
                          <option value="200">200</option>
                          <option value="50000">All</option>
                      </select>
                  </div>
                  <div class="table-container">
                      <table>
                          <thead>
                              <tr>
                                  <th scope="col">No</th>
                                  <th scope="col">Name</th>
                                  <th scope="col">User Name</th>
                                  <th scope="col">Phone Number</th>
                                  <th scope="col">Status</th>
                                  <th scope="col">Action</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr v-for="(user, index) in users" :key="index">
                                  <td class="whitespace-nowrap font-medium">
                                      {{ ++index + (currentPage - 1) * 20 }}
                                  </td>
                                  <td class="whitespace-nowrap">
                                      {{ user.name }}
                                  </td>
                                  <td class="whitespace-nowrap">
                                      {{ user.username }}
                                  </td>
                                  <td class="whitespace-nowrap">
                                      {{ user.phone_number }}
                                  </td>
                                  <td class="whitespace-nowrap">
                                      {{
                                          user.is_active
                                              ? "Active"
                                              : "Inactive"
                                      }}
                                  </td>
                                  <td class="whitespace-nowrap">
                                      <button
                                          class="mr-3 px-2 py-4"
                                          type="button"
                                          data-twe-toggle="modal"
                                          data-twe-target="#new_user"
                                          data-twe-ripple-init
                                          data-twe-ripple-color="light"
                                          @click="updateUser(user)"
                                      >
                                          <i class="fal fa-edit"></i>
                                      </button>
                                  </td>
                              </tr>
                          </tbody>
                      </table>
                      <div class="mt-6" v-if="getTotalCount > per_page">
                          <webPagination
                              :total-items-count="getTotalCount"
                              :items-per-page="per_page"
                              :current-page="currentPage"
                              active-color="#ffffff"
                              icon-color="#604f4f"
                              inactive-color="#604f4f"
                              disabled-color="#c8b5db"
                              @pageChanged="
                                  setCurrentPage($event);
                                  getUsers(false);
                              "
                          />
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div
          data-twe-modal-init
          class="fixed left-0 top-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
          id="new_user"
          tabindex="-1"
          aria-labelledby="ModalLabel"
          aria-hidden="true"
      >
          <div
              data-twe-modal-dialog-ref
              class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]"
          >
              <div
                  class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-4 outline-none"
              >
                  <div
                      class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 p-4"
                  >
                      <h5
                          class="text-xl font-medium leading-normal text-surface"
                          id="ModalLabel"
                      >
                          {{ new_user.id ? "Update User" : "Create User" }}
                      </h5>
                      <button
                          type="button"
                          id="closeModal"
                          class="box-content rounded-none border-none text-neutral-500 hover:text-neutral-800 hover:no-underline focus:text-neutral-800 focus:opacity-100 focus:shadow-none focus:outline-none"
                          data-twe-modal-dismiss
                          aria-label="Close"
                      >
                          <span class="[&>svg]:h-6 [&>svg]:w-6">
                              <svg
                                  xmlns="http://www.w3.org/2000/svg"
                                  fill="currentColor"
                                  viewBox="0 0 24 24"
                                  stroke-width="1.5"
                                  stroke="currentColor"
                              >
                                  <path
                                      stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M6 18L18 6M6 6l12 12"
                                  />
                              </svg>
                          </span>
                      </button>
                  </div>
                  <div
                      class="relative flex-auto px-4 py-2"
                      data-twe-modal-body-ref
                  >
                      <div class="">
                          <label for="" class="text-sm relative block"
                              >Name</label
                          >
                          <input
                              type="text"
                              placeholder="Name"
                              v-model="new_user.name"
                              class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                          />
                      </div>
                  </div>
                  <div
                      class="relative flex-auto px-4 py-2"
                      data-twe-modal-body-ref
                  >
                      <div class="">
                          <label for="" class="text-sm relative block"
                              >User Name</label
                          >
                          <input
                              type="text"
                              placeholder="Username"
                              v-model="new_user.username"
                              class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                          />
                      </div>
                  </div>
                  <div
                      class="relative flex-auto px-4 py-2"
                      data-twe-modal-body-ref
                  >
                      <div class="">
                          <label for="" class="text-sm relative block"
                              >Phone Number</label
                          >
                          <input
                              type="text"
                              placeholder="phone number"
                              v-model="new_user.phone_number"
                              class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                          />
                      </div>
                  </div>
                  <div
                      v-if="new_user.id"
                      class="relative flex-auto px-4 py-2"
                      data-twe-modal-body-ref
                  >
                      <div class="">
                          <label for="" class="text-sm relative block"
                              >Old Password</label
                          >
                          <input
                              type="password"
                              placeholder="old password"
                              v-model="new_user.old_password"
                              class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                          />
                      </div>
                  </div>
                  <div
                      class="relative flex-auto px-4 py-2"
                      data-twe-modal-body-ref
                  >
                      <div class="">
                          <label for="" class="text-sm relative block"
                              >Password</label
                          >
                          <input
                              type="password"
                              placeholder="Password"
                              v-model="new_user.password"
                              class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                          />
                      </div>
                  </div>
                  <div
                      class="relative flex-auto px-4 py-2"
                      data-twe-modal-body-ref
                  >
                      <div class="">
                          <label for="" class="text-sm mb-3 relative block"
                              >Confirm Password</label
                          >
                          <input
                              type="password"
                              placeholder="confirm password"
                              v-model="new_user.password_confirmation"
                              class="block w-full py-2 px-2 border border-gray-400 text-sm rounded-md bg-white focus:ring-0 focus:shadow-none"
                          />
                      </div>
                  </div>
                  <div class="px-4 py-4 flex items-center">
                      <!-- Toggle Switch -->
                      <label
                          class="relative inline-block w-12 h-6 cursor-pointer"
                      >
                          <input
                              type="checkbox"
                              class="hidden"
                              v-model="new_user.is_active"
                          />
                          <span
                              :class="{
                                  'bg-blue-500': new_user.is_active,
                                  'bg-gray-300': !new_user.is_active,
                              }"
                              class="block w-full h-full rounded-full transition-colors"
                          ></span>
                          <span
                              :class="{
                                  'translate-x-6': new_user.is_active,
                                  'translate-x-0': !new_user.is_active,
                              }"
                              class="absolute left-0 top-0 w-6 h-6 bg-white rounded-full shadow transform transition"
                          ></span>
                      </label>

                      <span class="ml-3 text-sm">Active </span>
                  </div>
                  <div class="px-4 py-2">
                      <label for="" class="text-sm mb-3 relative block"
                          >Allow Permissions</label
                      >
                      <div
                          v-for="permission in permissions"
                          :key="permission.id"
                          class="flex items-center mb-3"
                      >
                          <input
                              type="checkbox"
                              :id="`permission-${permission.id}`"
                              :value="permission.id"
                              v-model="allow_permissions"
                              class="hidden peer"
                          />
                          <div
                              class="w-5 h-5 rounded-md border-2 border-gray-300 flex items-center justify-center peer-checked:border-blue-500 peer-checked:bg-blue-500 transition-colors cursor-pointer"
                          >
                              <svg
                                  v-if="
                                      allow_permissions.includes(
                                          permission.id
                                      )
                                  "
                                  class="w-4 h-4 text-white"
                                  fill="none"
                                  stroke="currentColor"
                                  viewBox="0 0 24 24"
                                  xmlns="http://www.w3.org/2000/svg"
                              >
                                  <path
                                      stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M5 13l4 4L19 7"
                                  ></path>
                              </svg>
                          </div>
                          <label
                              :for="`permission-${permission.id}`"
                              class="ml-3 text-sm font-medium text-gray-700 cursor-pointer peer-hover:text-blue-500 transition-colors"
                          >
                              {{ permission.name }}
                          </label>
                      </div>
                  </div>
                  <div
                      class="flex flex-shrink-0 flex-wrap items-center justify-end border-t-2 border-neutral-100 p-4 gap-x-4"
                  >
                      <button
                          type="button"
                          class="inline-block rounded bg-primary-100 px-6 pb-2 pt-2.5 text-xs text-black focus:outline-none focus:ring-00"
                          data-twe-modal-dismiss
                          data-twe-ripple-init
                          id="modalClose"
                          data-twe-ripple-color="light"
                      >
                          Close
                      </button>
                      <button
                          type="button"
                          @click="createUser"
                          class="rounded bg-primary px-8 pb-2 pt-2.5 text-xs text-white hover:bg-primary-accent-300 focus:outline-none focus:ring-0 active:bg-primary-600"
                      >
                          {{ new_user.id ? "Update" : "Add" }}
                      </button>
                  </div>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
import { initTWE, Modal, Ripple, Dropdown } from "tw-elements";
import { mapGetters, mapMutations } from "vuex";
import { getApiData, postApiData } from "../../utilities/ajax-helpers";
import WebPagination from "../Common/webPagination.vue";
import SearchBox from "../Common/SearchBox.vue";
import moment from "moment";

export default {
  components: {
      WebPagination,
      SearchBox,
  },
  data() {
      return {
          users: [],
          search_input: "",
          new_user: {
              id: "",
              name: "",
              username: "",
              phone_number: "",
              old_password: "",
              password: "",
              password_confirmation: "",
              is_active: 1,
          },
          allow_permissions: [],
          permissions: [],
          per_page: 50,
      };
  },
  computed: {
      ...mapGetters(["getToken", "getTotalCount", "currentPage"]),
  },
  methods: {
      ...mapMutations(["setTotalCount", "setCurrentPage"]),

      async getUsers(reset_page) {
          if (reset_page) {
              this.setCurrentPage(1);
          }
          let url = `/api/users?search_input=${this.search_input}&page=${
              this.currentPage
          }${this.per_page ? `&per_page=${this.per_page}` : ""}`;
          let response = await getApiData({
              url: url,
              token: this.getToken,
          });
          if (response.data) {
              console.log(response.data);
              this.users = response.data.data;
              this.setTotalCount(response.data.total);
          }
      },
      async getPermissions() {
          let url = `/api/permissions`;
          let response = await getApiData({
              url: url,
              token: this.getToken,
          });
          if (response.data) {
              console.log(response.data);
              this.permissions = response.data;
          }
      },
      async createUser() {
          if (this.new_user.id) {
              //edit
              if (
                  !this.new_user.name ||
                  !this.new_user.phone_number ||
                  !this.new_user.username ||
                  this.allow_permissions.length == 0
              ) {
                  this.$notify({
                      title: "Error!",
                      text: "Please fill all forms!",
                      type: "error",
                  });
                  return;
              }
              if (
                  this.new_user.old_password ||
                  this.new_user.password ||
                  this.new_user.password_confirmation
              ) {
                  if (
                      !this.new_user.old_password ||
                      !this.new_user.password ||
                      !this.new_user.password_confirmation
                  ) {
                      this.$notify({
                          title: "Error!",
                          text: "Please fill all forms!",
                          type: "error",
                      });
                      return;
                  }
              }
          } else {
              //create
              if (
                  !this.new_user.name ||
                  !this.new_user.phone_number ||
                  !this.new_user.username ||
                  !this.new_user.password ||
                  !this.new_user.password_confirmation ||
                  this.allow_permissions.length == 0
              ) {
                  this.$notify({
                      title: "Error!",
                      text: "Please fill all forms!",
                      type: "error",
                  });
                  return;
              }
          }

          let formData = new FormData();
          if (this.new_user.id) {
              formData.append("id", this.new_user.id);
          }
          formData.append("name", this.new_user.name);
          formData.append("phone_number", this.new_user.phone_number);
          formData.append("username", this.new_user.username);
          if (this.new_user.password) {
              formData.append("password", this.new_user.password);
          }
          if (this.new_user.password_confirmation) {
              formData.append(
                  "password_confirmation",
                  this.new_user.password_confirmation
              );
          }

          formData.append("is_active", parseInt(this.new_user.is_active));
          formData.append(
              "permissions",
              JSON.stringify(this.allow_permissions)
          );

          if (this.new_user.id && this.new_user.old_password != "") {
              formData.append("old_password", this.new_user.old_password);
          }

          let url = "/api/users";
          let response = await postApiData({
              url: url,
              form_data: formData,
              token: this.getToken,
          });
          if (response.success) {
              this.$notify({
                  title: "Success!",
                  text: response.message,
                  type: "info",
              });
              this.getUsers(false);
              this.modalClose();
          } else {
              this.$notify({
                  title: "Error!",
                  text:
                      response?.message?.password ||
                      response?.message?.old_password ||
                      response?.message?.password_confirmation,
                  type: "error",
              });
          }
      },
      modalClose() {
          const button = document.getElementById("modalClose");
          if (button) {
              button.click();
          }
      },
      searchHandler(search_input) {
          this.search_input = search_input;
          this.getUsers(true);
      },
      formatDate(date) {
          if (date) {
              return moment(date).format("DD/MM/YYYY");
          }
      },
      updateUser(user) {
          this.new_user = {
              id: "",
              name: "",
              phone_number: "",
              username: "",
              old_password: "",
              password: "",
              password_confirmation: "",
              is_active: 1,
          };
          this.allow_permissions = [];
          if (user) {
              this.new_user.id = user.id;
              this.new_user.name = user.name;
              this.new_user.phone_number = user.phone_number;
              this.new_user.username = user.username;
              this.new_user.is_active = user.is_active;
              var ids = user.permissions.map((permission) => permission.id);
              this.allow_permissions = ids;
          }
      },
  },

  created() {},

  mounted() {
      this.getUsers(true);
      this.getPermissions();
      initTWE({ Modal, Ripple, Dropdown });
  },
};
</script>

<style src="node_modules/vue-multiselect/dist/vue-multiselect.css"></style>
