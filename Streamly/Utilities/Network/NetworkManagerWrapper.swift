//
//  NetworkManagerWrapper.swift
//  Streamly
//
//  Created by Rohit Singh on 5/12/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import Foundation
import Alamofire

public typealias SuccessClosure = (_ data: Data?) -> (Void)
public typealias FailureClosure = (_ error: Error?, _ data: Data?, _ statusCode: Int?) -> (Void)


class NetworkManagerWrapper: NSObject {
  
  static func makeRequest(_ requestParamter: RequestParameter, encoding: ParameterEncoding = URLEncoding.default, success: @escaping SuccessClosure, failure: @escaping FailureClosure) -> Request {

    let request = AF.request(requestParamter.url, method: requestParamter.method, parameters: requestParamter.parameters, encoding: encoding, headers: HTTPHeaders(requestParamter.headers ?? ["":""]))
    
    request.responseData { (dataResponse) in
      
      #if DEBUG
        if let data = dataResponse.value {
          let responseString = String(data: data, encoding: .utf8)
          if responseString != nil {
       //     debugPrint("Response string:\(responseString!)")
          }
          else {
          //  debugPrint("Response string is null")
          }
        }
      #endif

      if let error = dataResponse.error {
        debugPrint(error)
        failure(error, dataResponse.value, nil)
      }
      else if dataResponse.response?.statusCode == 200 || dataResponse.response?.statusCode == 201 {
        success(dataResponse.value)

      } else {
        // if status code is not 200 (success)
        failure(nil, dataResponse.value, dataResponse.response?.statusCode)
      }
    }
    return request
  }

}

