<!--
  This example requires Tailwind CSS v2.0+

  This example requires some changes to your config:

  ```
  // tailwind.config.js
  module.exports = {
    // ...
    plugins: [
      // ...
      require('@tailwindcss/forms'),
    ]
  }
  ```
-->
<div>
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Thông tin tài khoản</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Chỉnh sửa thông tin cá nhân của bạn
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form action="#" method="POST">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <div class="col-span-6 sm:col-span-4">
                            <label for="email_address" class="block text-sm font-medium text-gray-700">Email
                                address</label>
                            <input type="text" name="email_address" id="email_address" autocomplete="email"
                                   class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="first_name" class="block text-sm font-medium text-gray-700">Họ tên *</label>
                                <input type="text" name="first_name" id="first_name" autocomplete="given-name"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        <fieldset>
                            <div>
                                <legend class="text-base font-medium text-gray-900">Giới tính</legend>
                                <p class="text-sm text-gray-500">Lựa chọn giới tính của bạn.</p>
                            </div>
                            <div class="mt-4 space-y-4">
                                <div class="flex items-center">
                                    <input id="push_everything" name="push_notifications" type="radio"
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="push_everything" class="ml-3 block text-sm font-medium text-gray-700">
                                        Nam
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="push_email" name="push_notifications" type="radio"
                                           class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                    <label for="push_email" class="ml-3 block text-sm font-medium text-gray-700">
                                        Nữ
                                    </label>
                                </div>
                            </div>
                        </fieldset>
                        <div>
                            <link rel="stylesheet" href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css">
                            <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.js" defer></script>

                            <style>
                                [x-cloak] {
                                    display: none;
                                }
                            </style>

                            <div class="antialiased sans-serif">
                                <div x-data="app()" x-init="[initDate(), getNoOfDays()]" x-cloak>
                                    <div class="container mx-auto px-4 py-2 md:py-10">
                                        <div class="mb-5 w-64">

                                            <label for="datepicker" class="font-bold mb-1 text-gray-700 block">Ngày sinh</label>
                                            <div class="relative">
                                                <input type="hidden" name="date" x-ref="date">
                                                <input
                                                    type="text"
                                                    readonly
                                                    x-model="datepickerValue"
                                                    @click="showDatepicker = !showDatepicker"
                                                    @keydown.escape="showDatepicker = false"
                                                    class="w-full pl-4 pr-10 py-3 leading-none rounded-lg shadow-sm focus:outline-none focus:shadow-outline text-gray-600 font-medium"
                                                    placeholder="Select date">

                                                <div class="absolute top-0 right-0 px-3 py-2">
                                                    <svg class="h-6 w-6 text-gray-400"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>


                                                <!-- <div x-text="no_of_days.length"></div>
                                                <div x-text="32 - new Date(year, month, 32).getDate()"></div>
                                                <div x-text="new Date(year, month).getDay()"></div> -->

                                                <div
                                                    class="bg-white mt-12 rounded-lg shadow p-4 absolute top-0 left-0"
                                                    style="width: 17rem"
                                                    x-show.transition="showDatepicker"
                                                    @click.away="showDatepicker = false">

                                                    <div class="flex justify-between items-center mb-2">
                                                        <div>
                                                            <span x-text="MONTH_NAMES[month]" class="text-lg font-bold text-gray-800"></span>
                                                            <span x-text="year" class="ml-1 text-lg text-gray-600 font-normal"></span>
                                                        </div>
                                                        <div>
                                                            <button
                                                                type="button"
                                                                class="transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-200 p-1 rounded-full"
                                                                :class="{'cursor-not-allowed opacity-25': month == 0 }"
                                                                :disabled="month == 0 ? true : false"
                                                                @click="month--; getNoOfDays()">
                                                                <svg class="h-6 w-6 text-gray-500 inline-flex"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                                                </svg>
                                                            </button>
                                                            <button
                                                                type="button"
                                                                class="transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-200 p-1 rounded-full"
                                                                :class="{'cursor-not-allowed opacity-25': month == 11 }"
                                                                :disabled="month == 11 ? true : false"
                                                                @click="month++; getNoOfDays()">
                                                                <svg class="h-6 w-6 text-gray-500 inline-flex"  fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="flex flex-wrap mb-3 -mx-1">
                                                        <template x-for="(day, index) in DAYS" :key="index">
                                                            <div style="width: 14.26%" class="px-1">
                                                                <div
                                                                    x-text="day"
                                                                    class="text-gray-800 font-medium text-center text-xs"></div>
                                                            </div>
                                                        </template>
                                                    </div>

                                                    <div class="flex flex-wrap -mx-1">
                                                        <template x-for="blankday in blankdays">
                                                            <div
                                                                style="width: 14.28%"
                                                                class="text-center border p-1 border-transparent text-sm"
                                                            ></div>
                                                        </template>
                                                        <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">
                                                            <div style="width: 14.28%" class="px-1 mb-1">
                                                                <div
                                                                    @click="getDateValue(date)"
                                                                    x-text="date"
                                                                    class="cursor-pointer text-center text-sm leading-none rounded-full leading-loose transition ease-in-out duration-100"
                                                                    :class="{'bg-blue-500 text-white': isToday(date) == true, 'text-gray-700 hover:bg-blue-200': isToday(date) == false }"
                                                                ></div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <script>
                                    const MONTH_NAMES = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                    const DAYS = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

                                    function app() {
                                        return {
                                            showDatepicker: false,
                                            datepickerValue: '',

                                            month: '',
                                            year: '',
                                            no_of_days: [],
                                            blankdays: [],
                                            days: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],

                                            initDate() {
                                                let today = new Date();
                                                this.month = today.getMonth();
                                                this.year = today.getFullYear();
                                                this.datepickerValue = new Date(this.year, this.month, today.getDate()).toDateString();
                                            },

                                            isToday(date) {
                                                const today = new Date();
                                                const d = new Date(this.year, this.month, date);

                                                return today.toDateString() === d.toDateString() ? true : false;
                                            },

                                            getDateValue(date) {
                                                let selectedDate = new Date(this.year, this.month, date);
                                                this.datepickerValue = selectedDate.toDateString();

                                                this.$refs.date.value = selectedDate.getFullYear() +"-"+ ('0'+ selectedDate.getMonth()).slice(-2) +"-"+ ('0' + selectedDate.getDate()).slice(-2);

                                                console.log(this.$refs.date.value);

                                                this.showDatepicker = false;
                                            },

                                            getNoOfDays() {
                                                let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();

                                                // find where to start calendar day of week
                                                let dayOfWeek = new Date(this.year, this.month).getDay();
                                                let blankdaysArray = [];
                                                for ( var i=1; i <= dayOfWeek; i++) {
                                                    blankdaysArray.push(i);
                                                }

                                                let daysArray = [];
                                                for ( var i=1; i <= daysInMonth; i++) {
                                                    daysArray.push(i);
                                                }

                                                this.blankdays = blankdaysArray;
                                                this.no_of_days = daysArray;
                                            }
                                        }
                                    }
                                </script>
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="first_name" class="block text-sm font-medium text-gray-700">Điện thoại</label>
                                <input type="text" name="phone" id="phone" autocomplete="given-name"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="province" class="block text-sm font-medium text-gray-700">Tỉnh/Thành phố</label>
                            <select id="province" name="province" autocomplete="country"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option>United States</option>
                                <option>Canada</option>
                                <option>Mexico</option>
                            </select>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="district" class="block text-sm font-medium text-gray-700">Quận/Huyện</label>
                            <select id="district" name="district" autocomplete="country"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option>United States</option>
                                <option>Canada</option>
                                <option>Mexico</option>
                            </select>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <label for="ward" class="block text-sm font-medium text-gray-700">Phường/Xã</label>
                            <select id="ward" name="ward" autocomplete="country"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option>United States</option>
                                <option>Canada</option>
                                <option>Mexico</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-3 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="first_name" class="block text-sm font-medium text-gray-700">Địa chỉ</label>
                                <input type="text" name="address" id="address" autocomplete="given-name"
                                       class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Photo
                            </label>
                            <div class="mt-1 flex items-center">
                <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                  <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"/>
                  </svg>
                </span>
                                <button type="button"
                                        class="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Change
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Cover photo
                            </label>
                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                         viewBox="0 0 48 48" aria-hidden="true">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="file-upload"
                                               class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload a file</span>
                                            <input id="file-upload" name="file-upload" type="file" class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, GIF up to 10MB
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cập nhật
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


