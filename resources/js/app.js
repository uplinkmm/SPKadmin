import './bootstrap';
import '../css/app.css';

///////////// Tailwind section //////////////
import { Collapse, Carousel, initTWE, Modal, Ripple, Dropdown, Input, Tab } from 'tw-elements';
initTWE({ Collapse, Carousel, Modal, Ripple, Dropdown, Input, Tab});

//////////////................/////////////////

import {createApp} from 'vue/dist/vue.esm-bundler';
import { store } from './Store';
import Notifications from '@kyvg/vue3-notification';
import firebase from 'firebase/compat/app';
import 'firebase/compat/messaging';

const firebaseConfig = {
    apiKey: import.meta.env.VITE_GOOGLE_API_KEY,
    authDomain: import.meta.env.VITE_GOOGLE_AUTH_DOMAIN,
    projectId: import.meta.env.VITE_GOOGLE_PROJECT_ID,
    storageBucket: import.meta.env.VITE_GOOGLE_STORAGE_BUCKET,
    messagingSenderId: import.meta.env.VITE_GOOGLE_MESSAGING_SENDER_ID,
    appId: import.meta.env.VITE_GOOGLE_APP_ID,
    measurementId: import.meta.env.VITE_GOOGLE_MEASUREMENT_ID
};

const app = createApp({});
firebase.initializeApp(firebaseConfig);

import NavBarComponent from './Components/Common/NavBarComponent.vue';
import LoginComponent from './Components/Auth/LoginComponent.vue';
import TopupTransactionListComponent from './Components/TopupTransaction/TopupTransactionListComponent.vue';
import CashWithdrawalTransactionListComponent from './Components/CashWithdrawalTransaction/CashWithdrawalTransactionListComponent.vue';
import TwodBettingsOverviewComponent from './Components/TwodReports/TwodBettingsOverviewComponent.vue';
import TwodBettingAmountsComponent from './Components/TwodReports/TwodBettingAmountsComponent.vue';
import TwodBetListComponent from './Components/TwodReports/TwodBetListComponent.vue';
import TwodReportsListComponent from './Components/TwodReports/TwodReportsListComponent.vue';
import TwodWinnerListComponent from './Components/TwodReports/TwodWinnerListComponent.vue';
import TwodWinningNumberCrudComponent from './Components/TwodReports/TwodWinningNumberCrudComponent.vue';
import TwodClosingListComponent from './Components/TwodReports/TwodClosingListComponent.vue';

// three D
import ThreedBettingOverviewComponent from './Components/ThreedReports/ThreedBettingOverviewComponent.vue';
import ThreedWinningNumberCrudComponent from './Components/ThreedReports/ThreedWinningNumberCrudComponent.vue';
import ThreedBetListComponent from './Components/ThreedReports/ThreedBetListComponent.vue';
import ThreedBettingAmountsComponent from './Components/ThreedReports/ThreedBettingAmountsComponent.vue';
import ThreedCustomerBetsComponent from './Components/ThreedReports/ThreedCustomerBetsComponent.vue';
import ThreedWinnerListComponent from './Components/ThreedReports/ThreedWinnerListComponent.vue';
import GameSettingCrudComponent from './Components/ThreedReports/GameSettingCrudComponent.vue';
import ThreedClosingListComponent from './Components/ThreedReports/ThreedClosingListComponent.vue';

import LimitUserComponent from './Components/User/LimitUserComponent.vue';
import UserListComponent from './Components/User/UserListComponent.vue';
import BalanceTransactionListComponent from './Components/BalanceTransactionList/BalanceTransactionListComponent.vue';
import SettingComponent from './Components/Setting/SettingComponent.vue';
import PaymentProviderCrudComponent from './Components/PaymentProvider/PaymentProviderCrudComponent.vue';
import Games from './Components/Setting/games.vue';
import Deposit from './Components/History/Deposit.vue';
import Withdrawal from './Components/History/Withdrawal.vue';

import DepositsListComponent from './Components/Deposits/DepositsListComponent.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import AgentLists from './Components/Agents/AgentLists.vue';
import AgentsUsers from './Components/Agents/AgentsUsers.vue';
import GameTransitions from './Components/Agents/gameTransitions.vue';
import AgentsCommission from './Components/Agents/AgentsCommission.vue';
import AgentWallets from './Components/Agents/AgentWallets.vue';
import AgentsTranscationsStatus from './Components/Agents/AgentsTranscationsStatus.vue';
import Ads from './Components/Ads/Ads.vue';
import AdminUsers from './Components/User/AdminUsers.vue';
import { CkeditorPlugin } from '@ckeditor/ckeditor5-vue';
import TermsAndConditions from './Components/TermsAndConditions/TermsAndConditions.vue';

//Slots
import SlotTranscation from './Components/Slot/SlotTranscation.vue';


app.component('VueDatePicker', VueDatePicker);
app.component('NavBarComponent', NavBarComponent);
app.component('LoginComponent', LoginComponent);    
app.component('TopupTransactionListComponent', TopupTransactionListComponent);
app.component('CashWithdrawalTransactionListComponent', CashWithdrawalTransactionListComponent);
app.component('TwodBettingsOverviewComponent', TwodBettingsOverviewComponent);
app.component('TwodBettingAmountsComponent', TwodBettingAmountsComponent);
app.component('TwodBetListComponent', TwodBetListComponent);
app.component('TwodReportsListComponent', TwodReportsListComponent);
app.component('TwodWinnerListComponent', TwodWinnerListComponent);
app.component('TwodWinningNumberCrudComponent', TwodWinningNumberCrudComponent);
app.component('TwodClosingListComponent', TwodClosingListComponent);
app.component('Deposit', Deposit);
app.component('Withdrawal', Withdrawal);

// three D
app.component('ThreedBettingOverviewComponent', ThreedBettingOverviewComponent);
app.component('ThreedWinningNumberCrudComponent', ThreedWinningNumberCrudComponent);
app.component('ThreedBetListComponent', ThreedBetListComponent);
app.component('ThreedBettingAmountsComponent', ThreedBettingAmountsComponent);
app.component('ThreedCustomerBetsComponent', ThreedCustomerBetsComponent);
app.component('ThreedWinnerListComponent', ThreedWinnerListComponent);
app.component('GameSettingCrudComponent', GameSettingCrudComponent);
app.component('ThreedClosingListComponent', ThreedClosingListComponent);

app.component('LimitUserComponent', LimitUserComponent);
app.component('UserListComponent', UserListComponent);
app.component('BalanceTransactionListComponent', BalanceTransactionListComponent);
app.component('SettingComponent', SettingComponent);
app.component('PaymentProviderCrudComponent', PaymentProviderCrudComponent);
app.component('Games', Games);
app.component('DepositsListComponent', DepositsListComponent);
app.component('Ads', Ads);
app.component('AdminUsers', AdminUsers);

app.component('AgentLists', AgentLists);
app.component('AgentsUsers', AgentsUsers);
app.component('GameTransitions', GameTransitions);
app.component('AgentsCommission', AgentsCommission);
app.component('AgentWallets', AgentWallets);
app.component('AgentsTranscationsStatus', AgentsTranscationsStatus);
app.component('TermsAndConditions', TermsAndConditions);

// Slot
app.component('SlotTranscation', SlotTranscation);


app.use(store);
app.use(CkeditorPlugin);
app.use(Notifications);
app.mount('#app');

