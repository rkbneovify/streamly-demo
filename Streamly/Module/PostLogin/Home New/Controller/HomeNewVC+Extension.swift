//
//  HomeNewVC+Extension.swift
//  Streamly
//
//  Created by Aditya Joshi on 14/12/21.
//  Copyright © 2021 Rohit Singh. All rights reserved.
//

import UIKit
import Alamofire

// MARK: - UICollectionViewDelegate/ UICollectionViewDataSource
extension HomeNewVC : UICollectionViewDelegate, UICollectionViewDataSource, UICollectionViewDelegateFlowLayout {
    func numberOfSections(in collectionView: UICollectionView) -> Int
    {
        if self.collectionView == collectionView
        {
            guard let channels = filteredList, !channels.isEmpty else { return 0}
            return (channels.count + 1)
        }
        else {
            return 1
        }
    }
    
    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        if self.collectionView == collectionView
        {
            guard let data =  filteredList else { return 0}
            return ((section == 0) ? daysData : ((data[section - 1].programs?.count ?? 0) + 1))
        }
        else {
            guard let data =  self.genrsList?.allGenre else { return fixedCategoriesArray.count}
            return data.count + fixedCategoriesArray.count
        }
    }


    func collectionView(_ collectionView: UICollectionView, willDisplay cell: UICollectionViewCell, forItemAt indexPath: IndexPath) {
        //        if self.collectionView == collectionView
        //        {
        if indexPath.section == 0
        {
            let timeCell = cell as? TimeCollectionViewCell
            timeCell?.configureCell(indexInt: indexPath.row)
            timeCell?.layer.zPosition = 2000
            timeCell?.backgroundColor = .clear

        }
        else {

            if indexPath.row == 0
            {
                let channelCell = cell as? ChannelCollectionViewCell
                if  let  channel = filteredList?[indexPath.section - 1]
                {
                    channelCell?.configureWith(channel: channel)
                }
            }
            else {
                let programCell = cell as? ProgramCollectionViewCell
                
                if let channel = filteredList?[indexPath.section - 1], let programs = channel.programs
                {
                    self.headerTimeLbl.text = Utility.getDateFromTimeStampByFormat(from: filteredList?[indexPath.section - 1].programs?[indexPath.item - 1].start ?? 0.0, "E, d MMM yyyy")
                    programCell?.configureWithModel(program: programs[indexPath.item - 1], row : indexPath.row)
                } else {
                    
                }
            }
        }
        // }
    }
    
    func collectionView(_ collectionView: UICollectionView, cellForItemAt indexPath: IndexPath) -> UICollectionViewCell {
        if self.collectionView == collectionView
        {
            if indexPath.section == 0
            {
                guard let timeCell = collectionView.dequeueReusableCell(withReuseIdentifier: "timeCell", for: indexPath) as? TimeCollectionViewCell else {return UICollectionViewCell()}
                return timeCell
            }
            else {

                if indexPath.row == 0
                {
                    guard let channelCell = collectionView.dequeueReusableCell(withReuseIdentifier: "channelCell", for: indexPath) as? ChannelCollectionViewCell else {return UICollectionViewCell()}

                    return channelCell
                }
                else {
                    guard let programCell = collectionView.dequeueReusableCell(withReuseIdentifier: "programCell", for: indexPath) as? ProgramCollectionViewCell else {return UICollectionViewCell()}

                    return programCell
                }

            }
        }
        else {
            guard let cateGories = collectionView.dequeueReusableCell(withReuseIdentifier: AllGenrsListingCell.identifier, for: indexPath) as? AllGenrsListingCell else {return UICollectionViewCell()}
            switch indexPath.row {
            case CategoriesSelect.all.rawValue , CategoriesSelect.recent.rawValue,CategoriesSelect.myChannels.rawValue:
                cateGories.confiGureData(self.fixedCategoriesArray[indexPath.row], indexPath.row == self.selectedCategories ? true : false)

            default:
                if let data = genrsList?.allGenre
                {
                    cateGories.confiGureData(data[indexPath.row - 3], indexPath.row == self.selectedCategories ? true : false)

                }
            }

            return cateGories

        }
        
    }

    // set the Loading View
    func collectionView(_ collectionView: UICollectionView, viewForSupplementaryElementOfKind kind: String, at indexPath: IndexPath) -> UICollectionReusableView {
        if kind == UICollectionView.elementKindSectionFooter {
            if collectionView == self.collectionView {
                let aFooterView = collectionView.dequeueReusableSupplementaryView(ofKind: kind, withReuseIdentifier: "LoadingCollectionReusableView", for: indexPath) as? LoadingCollectionReusableView ?? LoadingCollectionReusableView()
                loadingView = aFooterView
                loadingView?.backgroundColor = UIColor.clear
                return aFooterView
            }
        }
        return UICollectionReusableView()
    }
    
    // start and stop the activityIndicator
      func collectionView(_ collectionView: UICollectionView, willDisplaySupplementaryView view: UICollectionReusableView, forElementKind elementKind: String, at indexPath: IndexPath) {
        if elementKind == UICollectionView.elementKindSectionFooter {
            if collectionView == self.collectionView {
            if showLoading {
                self.loadingView?.activityIndicator.startAnimating()
            } else {
                self.loadingView?.activityIndicator.stopAnimating()
            }
        }
      }
}
    
    // size of Loading View
    func collectionView(_ collectionView: UICollectionView, layout collectionViewLayout: UICollectionViewLayout, referenceSizeForFooterInSection section: Int) -> CGSize {
        if collectionView == self.collectionView {
            if !showLoading {
                return CGSize.zero
            } else {
                return CGSize(width: collectionView.bounds.size.width, height: 55)
            }
        } else {
            return CGSize.zero
        }
    }
    
    func collectionView(_ collectionView: UICollectionView, didEndDisplayingSupplementaryView view: UICollectionReusableView, forElementOfKind elementKind: String, at indexPath: IndexPath) {
        if elementKind == UICollectionView.elementKindSectionFooter && !showLoading {
            if collectionView == self.collectionView {
                self.loadingView?.activityIndicator.stopAnimating()
            }
        }
       }


    override func shouldUpdateFocus(in context: UIFocusUpdateContext) -> Bool {
        if self.collectionView == collectionView
        {
            if let chanelCell = context.nextFocusedView as? ChannelCollectionViewCell {
                guard let indexPath: IndexPath = self.collectionView.indexPath(for: chanelCell) as IndexPath? else { return true}
                // Call API for pagination
                
                if indexPath.section ==
                    (filteredList?.count ?? 0) && viewModel.isPageExist {
                    lastIndexPath = indexPath
                    showLoading = true
                    collectionView.reloadData()
                    viewModel.getEPGData(viewModel.currentPage)
                } else {
                    print("loading not called or pageExist is \(viewModel.isPageExist)")
                }
            }
            guard let programCell = context.nextFocusedView as? ProgramCollectionViewCell else {self.discriptionView.isHidden = true
                return true}
            guard let indexPath: IndexPath = self.collectionView.indexPath(for: programCell) as IndexPath? else { return true}
            if let channel = filteredList?[indexPath.section  - 1], let programs = channel.programs
            {
                self.headerTimeLbl.text = Utility.getDateFromTimeStampByFormat(from: programs[indexPath.item - 1].start ?? 0.0, "E, d MMM yyyy")
                self.discriptionView.isHidden = false
                self.discriptionHeader.text = programs[indexPath.item - 1].title ?? ""
                self.discriptionThumbNail.sd_setImage(with: URL(string: channel.logo ?? ""), placeholderImage: UIImage(named: "Logo2"))
                self.descriptionLbl.text =  programs[indexPath.item - 1].description ?? ""
                self.discriptionStartTimeLbl.text = "Starts at \(Utility.getDateFromTimeStampByFormat(from: programs[indexPath.item - 1].start ?? 0.0, "hh:mm a") ?? "")"
            }
            //print(indexPath)
           
            return true
        } else {
            return true
        }
    }

    func collectionView(_ collectionView: UICollectionView, shouldUpdateFocusIn context: UICollectionViewFocusUpdateContext) -> Bool {
        if self.collectionView == collectionView
        {
            self.collectionView.isScrollEnabled = false
            if context.nextFocusedIndexPath != nil && !collectionView.isScrollEnabled {
                if let nextFocusedIndexPath = context.nextFocusedIndexPath {
                    if nextFocusedIndexPath.section != 0 && nextFocusedIndexPath.row == 0
                    {
                        self.collectionView.isScrollEnabled = false
                        invalidateTimer()

                        collectionView.scrollToItem(
                            at: nextFocusedIndexPath,
                            at: .centeredVertically,
                            animated: true)
                        return true

                    }
                    else if nextFocusedIndexPath.section == 0
                    {
                        self.collectionView.isScrollEnabled = false
                        return true
                    }
                    else {
                        self.collectionView.isScrollEnabled = true
                        return true
                        //                    invalidateTimer()
                        //                    collectionView.scrollToItem(
                        //                        at: nextFocusedIndexPath,
                        //                        at: .centeredHorizontally,
                        //                        animated: false)
                    }
                }
            }
            return true
        }
        else {
            if context.nextFocusedIndexPath != nil && !collectionView.isScrollEnabled {
                if let nextFocusedIndexPath = context.nextFocusedIndexPath {
                    collectionView.scrollToItem(
                        at: nextFocusedIndexPath,
                        at: .centeredHorizontally,
                        animated: true)

                }

            }
            return true
        }

    }
    
    func collectionView(_ collectionView: UICollectionView, didUpdateFocusIn context: UICollectionViewFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        if let indexPath = context.nextFocusedIndexPath,
            let _ = collectionView.cellForItem(at: indexPath) {
            self.collectionView.scrollToItem(at: indexPath, at:
             .centeredVertically, animated: true)
        }
    }
    

    func collectionView(_ collectionView: UICollectionView, didSelectItemAt indexPath: IndexPath) {
        if self.collectionView == collectionView
        {
            if indexPath.section != 0
            {
                let flag = self.geoData?.success ?? false
                if self.geoData?.response_code == 402 {
                    showAlert(withMessage: "We're having some trouble with your current subscription. We'll try again, but in meantime you may want to update your subscription. For more help, please visit streamlytv.com or contact at https://streamlytv.com/helpandsupport.",setFirstButtonTitle: "Logout") { _ in
                        Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                        Utility.logOut()
                    }
                    return
                }
                guard let item = filteredList?[(indexPath.section - 1)] else {return}
                present(videoPlaybackViewController, animated: true) {
                    self.videoPlaybackViewController.channelObject = item
                    self.videoPlaybackViewController.isAllowedZipCode = flag
                    self.videoPlaybackViewController.delegatee = self
                    self.videoPlaybackViewController.str = self.geoData?.popupmsg ?? ""
                    self.videoPlaybackViewController.isPopAllow = self.geoData?.popupmsgtoggle ?? true
                    self.videoPlaybackViewController.isfromRcent = self.selectedCategories == 1 ? true : false
                    self.videoPlaybackViewController.idS = "\(item.id ?? 0)"
                    self.videoPlaybackViewController.filterData = self.filteredList
                    self.videoPlaybackViewController.loadAndPlay()
                }
                //                let viewController = PlayerVC.instantiateFromAppStoryboard(appStoryboard: .Main)
//                viewController.channelObject = item
//                viewController.isAllowedZipCode = flag
//                viewController.str = geoData?.popupmsg ?? ""
//                viewController.isPopAllow = self.geoData?.popupmsgtoggle ?? true
//                self.present(viewController, animated: true, completion: nil)
            }
        }
        else {
            switch indexPath.row {
            case CategoriesSelect.all.rawValue , CategoriesSelect.recent.rawValue,CategoriesSelect.myChannels.rawValue:
                self.selectCategories(indexPath.row, key: nil)

            default:
                self.selectCategories(indexPath.row, key: self.genrsList?.allGenre?[indexPath.row - 3])

            }

        }
    }
}

// MARK:  Timer
extension HomeNewVC {
    func startTimer()
    {
        timer = Timer.scheduledTimer(withTimeInterval: 5, repeats: true) { timer in
            self.getCurrentTime()
        }
    }
    
    func invalidateTimer()
    {
        if let _ = timer
        {
            timer!.invalidate()
            timer = nil
            setLiveBtnView(UIColor.lightGray,tittle: "GO LIVE")

        }
    }

    func setLiveBtnView(_ color:UIColor , tittle:String)
    {
        self.liveButton.setBackgroundImage(UIImage.imageWithColor(color), for: .normal)
        self.liveButton.setBackgroundImage(UIImage.imageWithColor(color), for: .focused)
        self.liveButton.setBackgroundImage(UIImage.imageWithColor(color), for: .highlighted)
        liveButton.setTitle(tittle, for: .normal)
    }
    
    func getCurrentTime()
    {
        let currentTime = formatter.string(from: Date())
        if currentTimeLabel.text != currentTime
        {
            currentTimeLabel.text = currentTime
            var offset = self.collectionView.contentOffset
            offset.x += 13.5
            self.collectionView.reloadData()
            self.collectionView.layoutIfNeeded()

            DispatchQueue.main.async(execute: {
                /* Scroll to the previous saved position */
                self.collectionView.setContentOffset(offset, animated: false)
            })

        }

    }
}
