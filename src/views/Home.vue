<template>
  <div class="home">
    <b-row>
      <b-col sm="8">
        <b-row>
          <b-col sm="12">
            <h2>Bulk SMS myMobKit</h2>
            <b-input-group class="mt-3" v-if="!serverset">
              <b-form-input v-model="server" placeholder="Adresa server myMobKit" type="url" required></b-form-input>
              <b-input-group-append>
                <b-button variant="outline-success" v-on:click="schimba">Gata</b-button>
              </b-input-group-append>
            </b-input-group>
            <div v-if="serverset" class="mt-2 text-muted">myMobKit Server: {{ server }} <small><b-link v-on:click="schimba">schimba</b-link></small></div>
          </b-col>
          <b-col sm="12">
            <b-input-group prepend="Numere" class="mt-3">
              <b-form-file
                :disabled="!serverset"
                accept="text/plain"
                :input="input"
                v-model="fisier"
                :state="Boolean(fisier)"
                placeholder="Choose a file or drop it here..."
                drop-placeholder="Drop file here..."
                @change="handleFile"
                id="numere"
              ></b-form-file>
            </b-input-group>
          </b-col>
          <b-col sm="12">
            <b-form-textarea style="margin-top: 10px;"
              :disabled="!serverset"
              id="textarea"
              v-model="text"
              placeholder="Enter something..."
              rows="3"
              max-rows="6"
            ></b-form-textarea>
          </b-col>
          <b-col>
            <b-form-group style="margin-top: 15px;">
              <b-form-checkbox-group
                :disabled="!serverset"
                v-model="selected"
                :options="options"
                switches
              ></b-form-checkbox-group>
            </b-form-group>
          </b-col>
          <b-col sm="2">
            <b-form-select :disabled="!serverset" v-model="slot" :options="slot_options" style="margin-top: 10px;"></b-form-select>
          </b-col>
          <b-col>
            <b-button v-on:click.prevent="sendSms" :disabled="!serverset || !text.length" block  style="margin-top: 10px;" variant="danger">Trimite SMS</b-button>
          </b-col>
        </b-row>
      </b-col>
      <b-col sm="4">
        <b-row>
          <b-col sm="12">
            <h2>Numere</h2>
          </b-col>
          <b-col sm="12">
            <table style="width: 100%;">
              <thead>
                <tr>
                  <th>#</th>
                </tr>
              </thead>
              <tbody v-if="numere.length">
                <tr v-for="(numar, index) in numere" :key="(index + 1)">
                  <td><pre>{{ (index + 1) }}. {{numar}}</pre> <code :id="numar"></code></td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr>
                  <td>
                    <p class="text-muted">0 numere</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </b-col>
        </b-row>
      </b-col>
    </b-row>
    <b-link v-on:click.prevent="iavezi">Iavezi</b-link>
  </div>
</template>
<script>
import axios from 'axios';
export default {
  name: 'Home',
  data() {
    return {
      server: '',
      serverset: false,
      slot: 1,
      slot_options: [
        { value: 1, text: 'SIM 1' },
        { value: 2, text: 'SIM 2' },
      ],
      selected: [],
      input: [],
      fisier: [],
      numere: [],
      text: '',
      options: [
        { text: 'Delivery Report', value: 1 }
      ]
    };
  },
  watch: {
    server(change) {
      localStorage.server = change;
    }
  },
  methods: {
    async deliver(form) {
      setTimeout( () => {
        return form;
      }, 1000)
    },
    sendSms() {
      var form = {
        to: null,
        message: this.text,
        deliveryreport: this.selected.length ? 1 : 0,
        slot: this.slot,
        server: this.server + '/services/api/messaging'
      };
      if(this.numere.length) {
        this.numere.forEach( (to) => {
          form.to = to;
          axios.post('https://sms.test/api/send.php', form, {
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' }
          })
          .then(response => response.data)
          .then( (data) => {
            if(data.isSuccessful) {
              
              //$('#' + to).html(data.isSuccessful)
              console.log(data.isSuccessful)
            }
            
          });
        })
      }
    },
    getDifference(a, b) {
      var i = 0;
      var j = 0;
      var result = "";

      while (j < b.length)
      {
        if (a[i] != b[j] || i == a.length)
            result += b[j];
        else
            i++;
        j++;
      }
      return result;
    },
    iavezi() {
      const nr = '0040756780624';
      const _this = this;
      $('tbody').find('code').each(function() {
        let element = $(this);
        let id = element.attr('id');

        console.log( _this.getDifference(nr, id) )
        if(id == nr) {
          console.log('E egal');
          element.html('Done!');
        } else {
          element.html('nu');
        }
      })
      
    },
    schimba() {
      if(this.serverset) {
        this.serverset = false
      } else {
        this.serverset = true
      }
    },
    handleFile(event) {
      const file = event.target.files[0];
      if (file.name != null) {
        const reader = new FileReader();
        var text;
        reader.onload = (e) => {
          text = e.target.result;
          var lines = text.split('\n');
          lines = lines.filter(String);
          lines.forEach( (line) => {
            line = line.replace(/ /g, "");
            line = line.replace('+', '00');
            this.numere.push(line);
          })
        };
        reader.readAsText(file, 'UTF-8');
      }
    },
  },
  mounted() {
    if (localStorage.server != null) {
      this.server = localStorage.server;
      this.serverset = true;
    }
  },
};
</script>
<style lang="css">
  pre {
    margin-bottom: 0 !important;
  }
</style>
