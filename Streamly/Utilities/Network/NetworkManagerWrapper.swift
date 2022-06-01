//
//  NetworkManagerWrapper.swift
//  Streamly
//
//  Created by Rohit Singh on 5/12/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation
import Alamofire

public typealias SuccessClosure = (_ data: Data?,_ status: Int?) -> (Void)
public typealias FailureClosure = (_ error: Error?, _ data: Data?, _ statusCode: Int?) -> (Void)
public typealias CancelAllPreviouseRequest = () -> (Void)


class NetworkManagerWrapper: NSObject {
  
  static func makeRequest(_ requestParamter: RequestParameter, encoding: ParameterEncoding = URLEncoding.default, success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {

    let request = AF.request(requestParamter.url, method: requestParamter.method, parameters: requestParamter.parameters, encoding: encoding, headers: HTTPHeaders(requestParamter.headers ?? ["":""]))
    print(request.convertible)
    request.responseData { (dataResponse) in
      
      #if DEBUG
        if let data = dataResponse.value {
          let responseString = String(data: data, encoding: .utf8)
          if responseString != nil {
            debugPrint("Response string:\(responseString!)")
          }
          else {
            debugPrint("Response string is null")
          }
        }
      #endif

      if let error = dataResponse.error {
        //debugPrint(error)
        failure(error, dataResponse.value, nil)
      }
      else if dataResponse.response?.statusCode == 200 || dataResponse.response?.statusCode == 201 {
        if requestParamter.url.contains("auth/channelgenre")
        {
            print("")
        }
        success(dataResponse.value, dataResponse.response?.statusCode)

      } else {
        // if status code is not 200 (success)
        failure(nil, dataResponse.value, dataResponse.response?.statusCode)
      }
    }
    return request
  }
    
    static func canceltheRequest(cancelRequest: CancelAllPreviouseRequest) {
        AF.cancelAllRequests()
        cancelRequest()
    }

    static func multiPartFormData(params: [String: Any],_ requestParamter: RequestParameter,loader:Bool = true, encoding: ParameterEncoding = URLEncoding.default, success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request  {

        let request = AF.upload(
            multipartFormData: { multipartFormData in
                for (key, value) in params {
                    if let temp = value as? String {
                        multipartFormData.append(temp.data(using: .utf8)!, withName: key)}

                    if let temp = value as? Int {
                        multipartFormData.append("(temp)".data(using: .utf8)!, withName: key)}

                    if let temp = value as? NSArray {
                        temp.forEach({ element in
                            let keyObj = key + "[]"
                            if let string = element as? String {
                                multipartFormData.append(string.data(using: .utf8)!, withName: keyObj)
                            } else
                            if let num = element as? Int {
                                let value = "(num)"
                                multipartFormData.append(value.data(using: .utf8)!, withName: keyObj)
                            }
                        })
                    }
                }
            },
            to: requestParamter.url, //URL Here
            method: requestParamter.method,
            headers: HTTPHeaders(requestParamter.headers ?? ["":""]))
        print(request.convertible)
        request.responseData { (dataResponse) in

            #if DEBUG
            if let data = dataResponse.value {
                let responseString = String(data: data, encoding: .utf8)
                if responseString != nil {
                    debugPrint("Response string:\(responseString!)")
                }
                else {
                    //  //debugPrint("Response string is null")
                }
            }
            #endif

            if let error = dataResponse.error {
                debugPrint(error)
                failure(error, dataResponse.value, nil)
            }
            else if dataResponse.response?.statusCode == 200 || dataResponse.response?.statusCode == 201 {
                success(dataResponse.value, dataResponse.response?.statusCode)

            } else {
                // if status code is not 200 (success)
                failure(nil, dataResponse.value, dataResponse.response?.statusCode)
            }
        }
        return request
    }
}

