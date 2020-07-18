package com.streamly.tv.search_channels;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.Log;
import android.view.View;
import androidx.databinding.DataBindingUtil;
import androidx.fragment.app.FragmentActivity;
import androidx.recyclerview.widget.GridLayoutManager;
import com.streamly.tv.R;
import com.streamly.tv.databinding.ActivitySearchBinding;
import com.streamly.tv.epg.ChannellistModel;
import com.streamly.tv.epg.Datum;
import com.streamly.tv.local_storage.TokenStorage;
import com.streamly.tv.log_in.LogInActivity;
import com.streamly.tv.retrofit_clients.ApiClient;
import com.streamly.tv.retrofit_clients.ApiInterface;
import com.streamly.tv.utilities.CommonUtility;
import com.streamly.tv.utilities.ConstantUtility;
import java.util.ArrayList;
import java.util.List;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class SearchActivity extends FragmentActivity {
    private static final String TAG= SearchActivity.class.getSimpleName();
    String authToken;
    ActivitySearchBinding searchBinding;
    List<SearchModel> searchList=new ArrayList<>();
    SearchAdapter  searchAdapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
//        setContentView(R.layout.activity_search);
        searchBinding= DataBindingUtil.setContentView(this, R.layout.activity_search);
        authToken = TokenStorage.readSharedToken(SearchActivity.this, ConstantUtility.AUTH_TOKEN, null);

        getChannelList();


        searchBinding.edSearch.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {

            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {

            }

            @Override
            public void afterTextChanged(Editable s) {
                searchAdapter.getFilter().filter((CharSequence) searchBinding.edSearch.getText().toString());
            }
        });
    }


    private void getChannelList() {
        searchBinding.progressBar.setVisibility(View.VISIBLE);
        ApiInterface apiInterface= ApiClient.getClient().create(ApiInterface.class);
        Call<ChannellistModel> call=apiInterface.getChannelList("Bearer "+authToken);
        call.enqueue(new Callback<ChannellistModel>() {
            @Override
            public void onResponse(Call<ChannellistModel> call, Response<ChannellistModel> response) {
                try{

                    if (response.isSuccessful() && response.body()!=null){

                     List<Datum> datumList=response.body().getData();

                     for (int i=0;i<datumList.size();i++){

                         String channelTitle=datumList.get(i).getCallSign();
                         String channelLogo=datumList.get(i).getLogo();
                         String videoUrl=datumList.get(i).getUrl();

                         searchList.add(new SearchModel(channelTitle,channelLogo));
                     }
                          searchAdapter= new SearchAdapter(SearchActivity.this, searchList);
                        searchBinding.rvSearch.setLayoutManager(new GridLayoutManager(SearchActivity.this,5));
                        searchBinding.rvSearch.setAdapter(searchAdapter);



                    }else if (response.code()==401){
                        CommonUtility.showToast(SearchActivity.this,response.body().getMessage());
                    }
                    else if (response.code()==404){
                        TokenStorage.clearSharedToken(SearchActivity.this);
                        CommonUtility.showToast(SearchActivity.this,ConstantUtility.SESSION_EXPIRED);
                        SearchActivity.this.startActivity(new Intent(SearchActivity.this, LogInActivity.class));
                        ((Activity)SearchActivity.this).finish();
                    }
                    else if (response.code()==500){
                        CommonUtility.showToast(SearchActivity.this,ConstantUtility.SERVER_ISSUE);
                    }
                    searchBinding.progressBar.setVisibility(View.INVISIBLE);
                }catch (Exception ex){
                    ex.printStackTrace();
                    Log.e(TAG, "Exception "+ex);
                }
                searchBinding.progressBar.setVisibility(View.INVISIBLE);
            }

            @Override
            public void onFailure(Call<ChannellistModel> call, Throwable t) {
                Log.e(TAG,"failure: "+call+" "+t.getLocalizedMessage());
                CommonUtility.showToast(SearchActivity.this,ConstantUtility.SERVER_ISSUE);
                searchBinding.progressBar.setVisibility(View.INVISIBLE);
            }
        });
    }

}