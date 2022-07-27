//
//  Request.swift
//  Streamly
//
//  Created by Rohit Singh on 5/18/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit
import Alamofire

class LoginRequest: NSObject {
    
    public var email: String?
    public var password: String?
    public var deviceID:String?
    
    func userLogin(complition: @escaping ((success: LoginResponse?, failure: FailedResponse?), String?) -> Swift.Void) {
        let parameter:[String:String] = ["email": self.email ?? "", "password": self.password ?? "","deviceid":deviceID ?? ""]
        let headers:HTTPHeaders = ["Content-Type": "application/x-www-form-urlencoded","User-Agent": userAgent]
        AF.request(Constant.baseURL + API.login.rawValue, method: .post, parameters: parameter, encoding: URLEncoding.default, headers: headers).responseJSON{ (response) in
            debugPrint(response)
            guard let data = response.data else { return }
            let decoder = JSONDecoder()
            if let loginData = try? decoder.decode(LoginResponse.self, from: data), response.response?.statusCode == 200 {
                complition((loginData, nil), nil)
            } else if let loginData = try? decoder.decode(FailedResponse.self, from: data) {
                complition((nil, loginData), ValidationErrorMessages.somethingWentWrong.descriptipon())
            } else {
                //debugPrint("Err", error.localizedDescription)
                complition((nil, nil), ValidationErrorMessages.somethingWentWrong.descriptipon())
            }
        }

    }

}
