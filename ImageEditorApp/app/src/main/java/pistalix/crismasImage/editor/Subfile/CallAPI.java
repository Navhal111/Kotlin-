package pistalix.crismasImage.editor.Subfile;

import android.util.Log;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import java.net.HttpURLConnection;
import java.net.URL;

public class CallAPI {
    final static int CONNECTION_TIME_OUT = 20 * 1000;
    public static String strURL = "http://fotoglobalsolution.com/androtech/service/app_link";

    private static String getResponseText(InputStream is) {
        BufferedReader br = null;
        StringBuilder sb = new StringBuilder();
        if (is != null) {


            int line;
            try {
                br = new BufferedReader(new InputStreamReader(is));
                while ((line = br.read()) != -1) {
                    sb.append((char) line);
                }
            } catch (IOException e) {
                StringBuilder error = new StringBuilder();
                error.append("Message = ").append(e.getMessage()).append("Cause = ").append(e.getCause());
                sb = error;
                e.printStackTrace();
            } finally {
                if (br != null) {
                    try {
                        br.close();
                        is.close();
                    } catch (IOException e) {
                        e.printStackTrace();
                        StringBuilder error = new StringBuilder();
                        error.append("Message = ").append(e.getMessage()).append("Cause = ").append(e.getCause());
                        sb = error;
                    }
                }
            }
        }
        return sb.toString();
    }

    public static void callGet(String token, String methodName, boolean isJWTNeeded, ResultCallBack resultCallBack) {

        URL url;
        HttpURLConnection urlConnection = null;
        int responseCode = 0;
        try {
            url = new URL(strURL + "/" + methodName);
            Log.i("CallAPI", "callGet: " + methodName);
            urlConnection = (HttpURLConnection) url.openConnection();
            urlConnection.setConnectTimeout(CONNECTION_TIME_OUT);
            if (isJWTNeeded)
                urlConnection.setRequestProperty("Authorization", "bearer " + token);   //JWT string
            //urlConnection.setRequestProperty("Content-Type", "application/json");
            /// urlConnection.setRequestProperty("Content-Language", "en-US");
            urlConnection.setUseCaches(false);
            urlConnection.setRequestMethod("GET");

            responseCode = urlConnection.getResponseCode();
            if (responseCode == 200) {
                String response = getResponseText(urlConnection.getInputStream());
                urlConnection.disconnect();
                resultCallBack.onSuccess(responseCode, response);
            } else {
                String response = getResponseText(urlConnection.getErrorStream());
                urlConnection.disconnect();
                resultCallBack.onFailure(responseCode, response);
            }

        } catch (Exception e) {
            e.printStackTrace();
            resultCallBack.onFailure(responseCode, getResponseText(urlConnection.getErrorStream()));

        }
    }

    public static void callPost(String token, String methodName, String rawData, boolean isJWTNeeded, ResultCallBack resultCallBack) {
        URL url;
        HttpURLConnection urlConnection = null;
        int responseCode = 0;
        try {
            url = new URL(strURL + "/" + methodName);
            urlConnection = (HttpURLConnection) url.openConnection();
            urlConnection.setConnectTimeout(CONNECTION_TIME_OUT);
            if (isJWTNeeded)
                urlConnection.setRequestProperty("Authorization", "bearer " + token);   //JWT string
            urlConnection.setRequestProperty("Content-Type", "application/json");
            urlConnection.setRequestProperty("Content-Language", "en-US");
            urlConnection.setUseCaches(false);
            urlConnection.setRequestMethod("POST");
            urlConnection.setDoOutput(true);

            OutputStream outputStream = urlConnection.getOutputStream();
            BufferedWriter writer = new BufferedWriter(new OutputStreamWriter(outputStream, "UTF-8"));
            writer.write(rawData);
            writer.flush();
            writer.close();
            outputStream.flush();
            outputStream.close();

            responseCode = urlConnection.getResponseCode();
            if (responseCode == 200) {
                String response = getResponseText(urlConnection.getInputStream());
                urlConnection.disconnect();
                resultCallBack.onSuccess(responseCode, response);
            } else {
                String response = getResponseText(urlConnection.getErrorStream());
                urlConnection.disconnect();
                resultCallBack.onFailure(responseCode, response);
            }


        } catch (Exception e) {
            e.printStackTrace();
            resultCallBack.onFailure(responseCode, getResponseText(urlConnection.getErrorStream()));

        }
    }

    public static void callPut(String token, String methodName, String rawData, boolean isJWTNeeded, ResultCallBack resultCallBack) {
        URL url;
        HttpURLConnection urlConnection = null;
        int responseCode = 0;
        try {
            url = new URL(strURL + "/" + methodName);
            urlConnection = (HttpURLConnection) url.openConnection();
            urlConnection.setConnectTimeout(CONNECTION_TIME_OUT);
            if (isJWTNeeded)
                urlConnection.setRequestProperty("Authorization", "bearer " + token);   //JWT string
            urlConnection.setRequestProperty("Content-Type", "application/json");
            urlConnection.setRequestProperty("Content-Language", "en-US");
            urlConnection.setUseCaches(false);
            urlConnection.setRequestMethod("PUT");

            OutputStream outputStream = urlConnection.getOutputStream();
            BufferedWriter writer = new BufferedWriter(new OutputStreamWriter(outputStream, "UTF-8"));
            writer.write(rawData);
            writer.flush();
            writer.close();
            outputStream.close();

            responseCode = urlConnection.getResponseCode();
            resultCallBack.onSuccess(responseCode, getResponseText(urlConnection.getInputStream()));

        } catch (Exception e) {
            e.printStackTrace();
            resultCallBack.onFailure(responseCode, getResponseText(urlConnection.getErrorStream()));
        }
    }

    public static void callDelete(String token, String methodName, String rawData, boolean isJWTNeeded, ResultCallBack resultCallBack) {
        URL url;
        HttpURLConnection urlConnection = null;
        int responseCode = 0;
        try {
            url = new URL(strURL + "/" + methodName);
            urlConnection = (HttpURLConnection) url.openConnection();
            urlConnection.setConnectTimeout(CONNECTION_TIME_OUT);
            if (isJWTNeeded)
                urlConnection.setRequestProperty("Authorization", "bearer " + token);   //JWT string
            urlConnection.setRequestProperty("Content-Type", "application/json");
            urlConnection.setRequestProperty("Content-Language", "en-US");
            urlConnection.setUseCaches(false);
            urlConnection.setRequestMethod("DELETE");

            OutputStream outputStream = urlConnection.getOutputStream();
            BufferedWriter writer = new BufferedWriter(new OutputStreamWriter(outputStream, "UTF-8"));
            writer.write(rawData);
            writer.flush();
            writer.close();
            outputStream.close();

            responseCode = urlConnection.getResponseCode();
            resultCallBack.onSuccess(responseCode, getResponseText(urlConnection.getInputStream()));

        } catch (Exception e) {
            e.printStackTrace();
            resultCallBack.onFailure(responseCode, getResponseText(urlConnection != null ? urlConnection.getErrorStream() : null));
        }
    }

    public interface ResultCallBack {
        void onSuccess(int responseCode, String strResponse);

        void onCancelled();

        void onFailure(int responseCode, String strResponse);
    }

}