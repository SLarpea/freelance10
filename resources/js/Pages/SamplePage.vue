<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import SearchLayout from "@/Layouts/SearchLayout.vue";
import { inject, ref } from "vue";

const swal = inject("$swal");
const global = inject('globalVar');
const formShow = ref(false);

const submit = () => {
  swal({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "question",
    showCancelButton: true,
    confirmButtonColor: '#90646E',
    cancelButtonText: '<i class="bi bi-hand-thumbs-down"></i> No',
    confirmButtonText: '<i class="bi bi-hand-thumbs-up"></i> Yes'
  }).then((result) => {
    if (result.isConfirmed) {
      global.isLoading = true;
      setTimeout(() => {
        global.isLoading = false;
        swal({
          title: "Deleted!",
          text: "Your file has been deleted.",
          icon: "success",
          showConfirmButton: false
        });
      }, 3000)
    }
  });
}
</script>

<template>
  <MainLayout title="Sample Page">
    <div class="page-card shadow">
      <div class="page-card-header">
        <span>
          <i class="bi bi-list-ol"></i>
          item list
        </span>
        <div class="btn-header">
          <button type="button" class="btn btn-primary btn-sm btn-delete">
            <i class="bi bi-trash"></i> Delete
          </button>
          <button type="button" class="btn btn-secondary btn-sm btn-add" @click.prevent="formShow = !formShow">
            <i class="bi bi-plus-lg"></i> Add
          </button>
        </div>
      </div>
      <SearchLayout />
      <div class="table-responsive">
        <table class="table table-sm table-striped table-bordered table-data">
          <colgroup>
            <col style="width: 1%" />
            <col style="width: 1%" />
            <col style="width: 15%" />
            <col style="width: '*'" />
            <col style="width: 3%" />
          </colgroup>
          <thead>
            <tr>
              <th>
                <input class='checkbox' id='checkbox' type="checkbox" />
                <label for='checkbox' class='checkbox'></label>
              </th>
              <th>#</th>
              <th>First</th>
              <th>Last</th>
              <th class="text-center">Handle</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <input class='checkbox' id='checkbox_1' type="checkbox" />
                <label for='checkbox_1' class='checkbox'></label>
              </td>
              <td>1</td>
              <td>Mark</td>
              <td>Otto</td>
              <td>
                <div class="btn-groups">
                  <button
                    type="button"
                    class="btn btn-xs dropdown-toggle rounded-none"
                    data-toggle="dropdown"
                  >
                    Select
                  </button>
                  <ul
                    class="text-sm dropdown-menu dropdown-menu-md dropdown-menu-right"
                  >
                    <li>
                      <button type="button" class="dropdown-item py-1.5">
                        <i class="bi bi-pencil-square mr-2"></i>Edit
                      </button>
                    </li>
                    <li>
                      <button type="button" class="dropdown-item py-1.5">
                        <i class="bi bi-eye mr-2"></i>View
                      </button>
                    </li>
                    <li>
                      <button
                        type="button"
                        class="text-danger dropdown-item py-1.5"
                      >
                        <i class="bi bi-trash mr-2"></i>Delete
                      </button>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <!-- <input type="checkbox" /> -->
                <input class='checkbox' id='checkbox_2' type="checkbox" />
                <label for='checkbox_2' class='checkbox'></label>
              </td>
              <td>2</td>
              <td>Jacob</td>
              <td>Thornton</td>
              <td>
                <div class="btn-groups">
                  <button
                    type="button"
                    class="btn btn-xs dropdown-toggle rounded-none"
                    data-toggle="dropdown"
                  >
                    Select
                  </button>
                  <ul
                    class="text-sm dropdown-menu dropdown-menu-md dropdown-menu-right"
                  >
                    <li>
                      <button type="button" class="dropdown-item py-1.5">
                        <i class="bi bi-pencil-square mr-2"></i>Edit
                      </button>
                    </li>
                    <li>
                      <button type="button" class="dropdown-item py-1.5">
                        <i class="bi bi-eye mr-2"></i>View
                      </button>
                    </li>
                    <li>
                      <button
                        type="button"
                        class="text-danger dropdown-item py-1.5"
                      >
                        <i class="bi bi-trash mr-2"></i>Delete
                      </button>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <!-- <input type="checkbox" /> -->
                <input class='checkbox' id='checkbox_3' type="checkbox" />
                <label for='checkbox_3' class='checkbox'></label>
              </td>
              <td>3</td>
              <td>Larry</td>
              <td>The Bird</td>
              <td>
                <div class="btn-groups">
                  <button
                    type="button"
                    class="btn btn-xs dropdown-toggle rounded-none"
                    data-toggle="dropdown"
                  >
                    Select
                  </button>
                  <ul
                    class="text-sm dropdown-menu dropdown-menu-md dropdown-menu-right"
                  >
                    <li>
                      <button type="button" class="dropdown-item py-1.5">
                        <i class="bi bi-pencil-square mr-2"></i>Edit
                      </button>
                    </li>
                    <li>
                      <button type="button" class="dropdown-item py-1.5">
                        <i class="bi bi-eye mr-2"></i>View
                      </button>
                    </li>
                    <li>
                      <button
                        type="button"
                        class="text-danger dropdown-item py-1.5"
                      >
                        <i class="bi bi-trash mr-2"></i>Delete
                      </button>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <!-- <input type="checkbox" /> -->
                <input class='checkbox' id='checkbox_4' type="checkbox" />
                <label for='checkbox_4' class='checkbox'></label>
              </td>
              <td>3</td>
              <td>Larry</td>
              <td>The Bird</td>
              <td>
                <div class="btn-groups">
                  <button
                    type="button"
                    class="btn btn-xs dropdown-toggle rounded-none"
                    data-toggle="dropdown"
                  >
                    Select
                  </button>
                  <ul
                    class="text-sm dropdown-menu dropdown-menu-md dropdown-menu-right"
                  >
                    <li>
                      <button type="button" class="dropdown-item py-1.5">
                        <i class="bi bi-pencil-square mr-2"></i>Edit
                      </button>
                    </li>
                    <li>
                      <button type="button" class="dropdown-item py-1.5">
                        <i class="bi bi-eye mr-2"></i>View
                      </button>
                    </li>
                    <li>
                      <button
                        type="button"
                        class="text-danger dropdown-item py-1.5"
                      >
                        <i class="bi bi-trash mr-2"></i>Delete
                      </button>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <!-- <input type="checkbox" /> -->
                <input class='checkbox' id='checkbox_5' type="checkbox" />
                <label for='checkbox_5' class='checkbox'></label>
              </td>
              <td>3</td>
              <td>Larry</td>
              <td>The Bird</td>
              <td>
                <div class="btn-groups">
                  <button
                    type="button"
                    class="btn btn-xs dropdown-toggle rounded-none"
                    data-toggle="dropdown"
                  >
                    Select
                  </button>
                  <ul
                    class="text-sm dropdown-menu dropdown-menu-md dropdown-menu-right"
                  >
                    <li>
                      <button type="button" class="dropdown-item py-1.5">
                        <i class="bi bi-pencil-square mr-2"></i>Edit
                      </button>
                    </li>
                    <li>
                      <button type="button" class="dropdown-item py-1.5">
                        <i class="bi bi-eye mr-2"></i>View
                      </button>
                    </li>
                    <li>
                      <button
                        type="button"
                        class="text-danger dropdown-item py-1.5"
                      >
                        <i class="bi bi-trash mr-2"></i>Delete
                      </button>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <!-- <input type="checkbox" /> -->
                <input class='checkbox' id='checkbox_6' type="checkbox" />
                <label for='checkbox_6' class='checkbox'></label>
              </td>
              <td>3</td>
              <td>Larry</td>
              <td>The Bird</td>
              <td>
                <div class="btn-groups">
                  <button
                    type="button"
                    class="btn btn-xs dropdown-toggle rounded-none"
                    data-toggle="dropdown"
                  >
                    Select
                  </button>
                  <ul
                    class="text-sm dropdown-menu dropdown-menu-md dropdown-menu-right"
                  >
                    <li>
                      <button type="button" class="dropdown-item py-1.5">
                        <i class="bi bi-pencil-square mr-2"></i>Edit
                      </button>
                    </li>
                    <li>
                      <button type="button" class="dropdown-item py-1.5">
                        <i class="bi bi-eye mr-2"></i>View
                      </button>
                    </li>
                    <li>
                      <button
                        type="button"
                        class="text-danger dropdown-item py-1.5"
                      >
                        <i class="bi bi-trash mr-2"></i>Delete
                      </button>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <!-- <input type="checkbox" /> -->
                <input class='checkbox' id='checkbox_7' type="checkbox" />
                <label for='checkbox_7' class='checkbox'></label>
              </td>
              <td>3</td>
              <td>Larry</td>
              <td>The Bird</td>
              <td>
                <div class="btn-groups">
                  <button
                    type="button"
                    class="btn btn-xs dropdown-toggle rounded-none"
                    data-toggle="dropdown"
                  >
                    Select
                  </button>
                  <ul
                    class="text-sm dropdown-menu dropdown-menu-md dropdown-menu-right"
                  >
                    <li>
                      <button type="button" class="dropdown-item py-1.5">
                        <i class="bi bi-pencil-square mr-2"></i>Edit
                      </button>
                    </li>
                    <li>
                      <button type="button" class="dropdown-item py-1.5">
                        <i class="bi bi-eye mr-2"></i>View
                      </button>
                    </li>
                    <li>
                      <button
                        type="button"
                        class="text-danger dropdown-item py-1.5"
                      >
                        <i class="bi bi-trash mr-2"></i>Delete
                      </button>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div>
        <span>Hello</span>
      </div>
    </div>
  </MainLayout>
  <form>
  <transition name="modal-fade">
    <div v-if="formShow" class="modal custom-modal">
      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h6>Add Data</h6>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click.prevent="formShow = false">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group floating">
                <input type="text" class="form-control floating" id="first" placeholder="First">
                <label for="first">First</label>
            </div>
            <div class="form-group floating">
                <input type="text" class="form-control floating" id="last" placeholder="Last">
                <label for="last">Last</label>
            </div>
          </div>
          <div class="modal-footer">
            <span class="text-xs">Add some notes here.</span>
            <div class="btn-footer">
              <button type="button" class="btn btn-primary btn-sm btn-submit" @click.prevent="submit">
                Submit
              </button>
              <button type="button" class="btn btn-secondary btn-sm btn-close" @click.prevent="formShow = !formShow">
               Close
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </transition>
  </form>
</template>